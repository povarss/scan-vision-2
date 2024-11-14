<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamSettingRequest;
use App\Models\Exam;
use App\Models\PatientExam;
use App\Services\SvgFillerService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function getTestPattern(PatientExam $patientExam)
    {
        $filler = new SvgFillerService($patientExam);
        return $filler->makePrint();
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
}
