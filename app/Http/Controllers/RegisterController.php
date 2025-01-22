<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFirstStageRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\RegistrationSecondStageRequest;
use App\Models\Patient;
use App\Models\PatientAnswer;
use App\Models\PromoCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function checkFirst(RegistrationFirstStageRequest $request)
    {
        return 1;
    }

    public function checkSecond(RegistrationSecondStageRequest $request)
    {
        return 1;
    }

    public function store(RegistrationRequest $request)
    {
        DB::beginTransaction();
        $promoCode  = PromoCode::where('code', $request->promoCode)->first();

        $expireDate = Carbon::now()->addDays($promoCode->day)->format('Y-m-d');

        $user = User::createPatientUser($request->nick_name, $request->email, $request->password, $expireDate);

        $patient = new Patient();
        $patient->doctor_id = 0;
        $patient->full_name = $request->name;
        $patient->nick_name = $request->nick_name;
        $patient->phone = $request->phone;
        $patient->region_id = $request->region_id;
        $patient->save();

        $promoCode->activate($patient);

        foreach ($request->answers as $answer) {
            $patientAnswer = new PatientAnswer();
            $patientAnswer->patient_id = $patient->id;
            $patientAnswer->question_id = $answer['question_id'];
            $patientAnswer->answer_id = $answer['answer_id'];
            $patientAnswer->save();
        }


        DB::commit();
    }
}
