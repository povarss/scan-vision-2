<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamSettingRequest;
use App\Models\Exam;
use App\Models\PatientExam;
use App\Services\SvgFillerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function getTestPattern(PatientExam $patientExam)
    {
        $isNew = empty($patientExam->pattern);
        if ($isNew) {
            $filler = new SvgFillerService($patientExam);
            $pattern = $filler->makePrint();
            $patientExam->pattern = $pattern;
            $patientExam->start_time = date('Y-m-d H:i:s');
            $patientExam->save();
        }
        return response()->json([
            'pattern' => $patientExam->pattern,
            'selected' => $patientExam->result ?? []
        ]);
    }

    public function storeSettings(StoreExamSettingRequest $request, Exam $exam)
    {
        $fields = $request->validated();
        $patientExam = new PatientExam();
        $patientExam->fill($fields);
        $patientExam->setDraftStatus();
        $patientExam->exam_id = $exam->getMainTest()->id;
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
        $testMinute = intval(ceil($difSeconds / 60));

        $correctTotal = 0;
        foreach ($patientExam->pattern as $row) {
            foreach ($row as $item) {
                if ($item['isCorrect']) {
                    $correctTotal++;
                }
            }
        }

        $correctCount = ['left' => 0, 'right' => 0, 'total' => $correctTotal, 'selected' => 0];
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
        return [
            'patientId' => $patientExam->patient_id,
            'totalMinute' => $patientExam->time,
            'testMinute' => $testMinute,
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
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
        $pdf = Pdf::loadView('pdf.exam', compact('patientExam', 'totals'))->setWarnings(true);
        return $pdf->stream();
    }
}
