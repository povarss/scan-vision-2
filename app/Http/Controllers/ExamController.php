<?php

namespace App\Http\Controllers;

use App\Dto\ExamResultDto;
use App\Http\Requests\StartExamRequest;
use App\Http\Requests\StoreExamSettingRequest;
use App\Models\Exam;
use App\Models\ExamTimes;
use App\Models\PatientExam;
use App\Models\PatientSettings;
use App\Models\Reference;
use App\Services\GetRecommendationService;
use App\Services\PatternMakerService;
use App\Services\SvgFillerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ExamController extends Controller
{
    public function getReferences(string $id)
    {
        $patientExam = PatientExam::where('id', $id)->first();
        if ($patientExam->status !== PatientExam::STATUS_DRAFT) {
            abort(500, 'Test finished');
        }
        $examConfigs = config('exam');
        $examOptionConfigs = config('exam_options');
        $patientSettingValue = PatientSettings::where('patient_id', $patientExam->patient_id)->first();

        $examType = Exam::where('id', $patientExam->exam_id)->first();

        $resultOptions = $examConfigs[$patientExam->exam_id];
        $resultOptions['title'] = $examType ? $examType->label : '';
        $resultOptions['type'] = $patientExam->type;
        $resultOptions['options'] = $examOptionConfigs;
        if (!empty($patientExam->custom_settings)) {
            $resultOptions['storedValues'] =  $patientExam->custom_settings ?? [];
        } else {
            $resultOptions['storedValues'] =  $patientSettingValue?->settings ?? [];
        }
        return response()->json($resultOptions);
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

        $isRestart = $request->isRestart;
        if ($isNew || $isRestart) {
            $patientExam->pattern = null;
            $width  = intval(floor($request->width));
            $height  = intval(floor($request->height));
            $filler = new PatternMakerService($patientExam, $width, $height);
            $filler->makePattern();
            $patientExam->pattern = $filler->getPattern();
            $patientExam->pattern_additional_items = $filler->getAdditionalItems();
            $patientExam->start_time = date('Y-m-d H:i:s');
            $patientExam->width = $width;
            $patientExam->height = $height;
            $patientExam->save();
        }
        if ($request->isStart) {
            if (!$isNew) {
                $patientExam->counter += 1;
                $patientExam->save();
            }
            ExamTimes::start($patientExam);
        }

        $examConfigs = config('exam');
        return response()->json([
            'pattern' => $patientExam->pattern,
            'type' => $patientExam->type,
            'selected' => $patientExam->result ?? [],
            'width' => $patientExam->width,
            'height' => $patientExam->height,
            'configs' => $examConfigs[$patientExam->exam_id],
            'custom_settings' => $patientExam->custom_settings,
            'pattern_additional_items' => $patientExam->pattern_additional_items,
            'doubleClicks' => collect($patientExam->double_clicks)->keyBy(function (array $item, int $key) {
                return $item[0] . '_' . $item[1];
            })
        ]);
    }

    public function makeDraft(StartExamRequest $request, PatientExam $patientExam)
    {
        $newExam = $patientExam->createDraft($request->input('patient_id'), $request->input('type'), $request->input('exam_id'));
        return response()->json(['id' => $newExam->id]);
    }

    public function storeSettings(StoreExamSettingRequest $request, Exam $exam)
    {
        $fields = $request->validated();
        $patientExam = PatientExam::where('id', $request->id)->first();
        $patientExam->fill($fields);
        $patientExam->setDraftStatus();
        if ($patientExam->type == PatientExam::TYPE_WITH_DOTS) {
            $custom_settings = [];
            $props = [
                PatientSettings::ST_DIRECTION,
                PatientSettings::ST_SPEED,
                PatientSettings::ST_COLOR,
                PatientSettings::ST_DOT_COUNT
            ];
            foreach ($props as $prop) {
                $custom_settings[$prop] = $request->input($prop);
            }
            $patientExam->custom_settings = $custom_settings;
            PatientSettings::storeParams($patientExam->patient_id, $patientExam->custom_settings);
        }
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
        $patientExam->double_clicks = $request->double_clicks;
        $patientExam->end_time = date('Y-m-d H:i:s');
        $patientExam->setFinished();
        $patientExam->save();
        ExamTimes::setTime($patientExam, true);
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

        $patternMaker = new PatternMakerService($patientExam, $patientExam->width, $patientExam->height);
        $correctTotals = $patternMaker->calcTotals();
        [$correctCount, $incorrectCount] = $patternMaker->getCorrectTotals();
        $correctCount['total'] = $correctTotals['total'];

        $examResultDto = new ExamResultDto(
            $patientExam->exam_id,
            $correctTotals['total'] - $correctCount['selected'],
            $correctTotals['left'] - $correctCount['left'],
            $correctTotals['right'] - $correctCount['right'],

            $incorrectCount['total'],
            $incorrectCount['left'],
            $incorrectCount['right']
        );
        $analyzeInfo = (new GetRecommendationService($examResultDto))->getInformation();
        $timeExpiredExamId = Cache::pull('time_expired_exam_id');
        return [
            'patientId' => $patientExam->patient_id,
            'totalMinute' => $patientExam->time,
            'testMinute' => $testMinute,
            'testSecond' => $testSecond,
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'type' => $patientExam->exam_id,
            'typeLabel' => Exam::where('id', $patientExam->exam_id)->first()->label,
            'exam_type_recommend' => $patientExam->getExamTypeRecommendLabel(),
            'analyzeInfo' => $analyzeInfo,
            'showTimeNotification' => ($timeExpiredExamId ==  $patientExam->id ? 1 : 0),
            'exam_id' => $patientExam->exam_id,
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
        $doubleClicks = collect($patientExam->double_clicks)->keyBy(function (array $item, int $key) {
            return $item[0] . '_' . $item[1];
        });
        $isWithMap = $patientExam->exam_id != 4;
        $pdf = Pdf::loadView('pdf.exam', compact('patientExam', 'totals', 'config', 'reference', 'doubleClicks', 'isWithMap'))->setWarnings(true);
        // return view('pdf.exam', compact('patientExam', 'totals', 'config','reference'));
        return $pdf->stream();
    }

    public function setTime(PatientExam $patientExam)
    {
        $userTime = ExamTimes::setTime($patientExam);
        $leftSeconds = $userTime->limited_time - $userTime->used_time;

        if ($leftSeconds <= 0) {
            Cache::put('time_expired_exam_id', $patientExam->id);
        }

        return [
            'left_seconds' => $leftSeconds
        ];
    }
}
