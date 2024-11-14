<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamSettingRequest;
use App\Models\Exam;
use App\Models\PatientExam;
use App\Services\SvgFillerService;
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

    public function getInfo(PatientExam $patientExam)
    {
        $difSeconds = Carbon::parse($patientExam->start_time)->diffInSeconds(Carbon::parse($patientExam->end_time));
        $testMinute = intval(ceil($difSeconds / 60));
        $correctCount = ['left' => 0, 'right' => 0, 'total' => 0];
        $incorrectCount = ['left' => 0, 'right' => 0, 'total' => 0];

        foreach ($patientExam->result as $key => $position) {
            $item = $patientExam->pattern[$position[0]][$position[1]];
            if ($item['isCorrect']) {
                $correctCount['total'] = $correctCount['total'] + 1;
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
        return response()->json([
            'totalMinute' => $patientExam->time,
            'testMinute' => $testMinute,
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
        ]);
    }
}
