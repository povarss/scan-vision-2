<?php

namespace App\Http\Controllers;

use App\Dto\ExamResultDto;
use App\Http\Requests\StoreExamSettingRequest;
use App\Models\Exam;
use App\Models\PatientExam;
use App\Models\Reference;
use App\Services\GetRecommendationService;
use App\Services\SvgFillerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function getReferences(string $type)
    {
        $examConfigs = config('exam');

        $examType = Exam::where('id', $type)->first();
        $examConfigs[$type]['title'] = $examType ? $examType->label : '';
        return response()->json($examConfigs[$type]);
    }
    public function checkPattern(PatientExam $patientExam)
    {
        $filler = new SvgFillerService($patientExam, $patientExam->width, $patientExam->height);
        $filler->checkPattern();
        exit;
    }
    public function getTestPattern(Request $request, PatientExam $patientExam)
    {
        $isNew = empty($patientExam->pattern);

        if ($isNew) {
            $width  = intval(floor($request->width));
            $height  = intval(floor($request->height));
            $filler = new SvgFillerService($patientExam, $width, $height);
            $pattern = $filler->makePrint();
            $patientExam->pattern = $pattern;
            $patientExam->start_time = date('Y-m-d H:i:s');
            $patientExam->width = $width;
            $patientExam->height = $height;
            $patientExam->save();
        }
        $examConfigs = config('exam');
        return response()->json([
            'pattern' => $patientExam->pattern,
            'selected' => $patientExam->result ?? [],
            'width' => $patientExam->width,
            'height' => $patientExam->height,
            'configs' => $examConfigs[$patientExam->exam_id],
        ]);
    }

    public function storeSettings(StoreExamSettingRequest $request, Exam $exam)
    {
        $fields = $request->validated();
        $patientExam = new PatientExam();
        $patientExam->fill($fields);
        $patientExam->setDraftStatus();
        $patientExam->exam_id = $request->exam_id; // $exam->getMainTest()->id;
        $patientExam->save();

        return response()->json([
            'message' => 'Exam created successfully',
            'exam' => $patientExam
        ]);
    }

    public function storeResult(Request $request)
    {
        $patientExam = PatientExam::where('id', $request->id)->first();
        $patientExam->result = $request->result;
        $patientExam->end_time = date('Y-m-d H:i:s');
        $patientExam->setFinished();
        $patientExam->save();
    }

    public function getDetail(PatientExam $patientExam)
    {
        return [
            'id' => $patientExam->id,
            'isFinished' => $patientExam->status == PatientExam::STATUS_FINISHED,
        ];
    }

    public function getResultData($patientExam)
    {
        $difSeconds = Carbon::parse($patientExam->start_time)->diffInSeconds(Carbon::parse($patientExam->end_time));
        $testMinute = intval(floor($difSeconds / 60));
        $testSecond = $difSeconds - $testMinute * 60;


        $correctTotals = ['total' => 0, 'left' => 0, 'right' => 0];
        foreach ($patientExam->pattern as $row) {
            foreach ($row as $item) {
                if ($item['isCorrect']) {
                    $correctTotals['total'] = $correctTotals['total'] + 1;
                    if (in_array($item['section'], [1, 2, 7, 8])) {
                        $correctTotals['right'] = $correctTotals['right'] + 1;
                    } else {
                        $correctTotals['left'] = $correctTotals['left'] + 1;
                    }
                }
            }
        }

        $correctCount = ['left' => 0, 'right' => 0, 'total' => $correctTotals['total'], 'selected' => 0];
        $incorrectCount = ['left' => 0, 'right' => 0, 'total' => 0];

        foreach ($patientExam->result as $key => $position) {
            $item = $patientExam->pattern[$position[0]][$position[1]];
            if ($item['isCorrect']) {
                $correctCount['selected'] = $correctCount['selected'] + 1;
                if (in_array($item['section'], [1, 2, 7, 8])) {
                    $correctCount['right'] = $correctCount['right'] + 1;
                } else {
                    $correctCount['left'] = $correctCount['left'] + 1;
                }
            } else {
                $incorrectCount['total'] = $incorrectCount['total'] + 1;
                if (in_array($item['section'], [1, 2, 7, 8])) {
                    $incorrectCount['right'] = $incorrectCount['right'] + 1;
                } else {
                    $incorrectCount['left'] = $incorrectCount['left'] + 1;
                }
            }
        }
        $examResultDto = new ExamResultDto(
            $patientExam->exam_id,
            $correctTotals['total'] - $correctCount['selected'],
            $correctTotals['left'] - $correctCount['right'],
            $correctTotals['right'] - $correctCount['right'],

            $incorrectCount['total'],
            $incorrectCount['left'],
            $incorrectCount['right']
        );
        $analyzeInfo = (new GetRecommendationService($examResultDto))->getInformation();
        return [
            'patientId' => $patientExam->patient_id,
            'totalMinute' => $patientExam->time,
            'testMinute' => $testMinute,
            'testSecond' => $testSecond,
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'type' => $patientExam->exam_id,
            'typeLabel' => Exam::where('id', $patientExam->exam_id)->first()->label,
            'analyzeInfo' => $analyzeInfo,
        ];
    }
    public function getInfo(PatientExam $patientExam)
    {
        return response()->json($this->getResultData($patientExam));
    }

    public function print(PatientExam $patientExam)
    {
        $totals = $this->getResultData($patientExam);
        // return view('pdf.exam', compact('patientExam', 'totals'));
        $examConfigs = config('exam');
        $config = $examConfigs[$patientExam->exam_id];
        $reference = Reference::get()->keyBy('id')->groupBy('key_', true);
        $pdf = Pdf::loadView('pdf.exam', compact('patientExam', 'totals', 'config', 'reference'))->setWarnings(true);
        // return view('pdf.exam', compact('patientExam', 'totals', 'config','reference'));
        return $pdf->stream();
    }
}
