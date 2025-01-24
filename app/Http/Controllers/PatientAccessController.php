<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientAccessRequest;
use App\Http\Resources\PatientAccessResource;
use App\Models\Patient;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientAccessController extends Controller
{
    public function store(StorePatientAccessRequest $request)
    {
        $fields = $request->validated();
        DB::beginTransaction();


        $patient = Patient::findOrFail($request->id);
        $patient->save();

        if (empty($patient->user_id)) {
            $user = new User();
            $user->name = $patient->full_name;
        } else {
            $user = $patient->user;
        }
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if (empty($user->password)) {
            $user->password = Hash::make(Str::random(7));
        }
        $user->save();

        $user->assignRole(User::ROLE_PATIENT);

        $patient->user_id = $user->id;
        $patient->save();

        Subscription::store($user, $request->expire_at, $request->minutes);
        DB::commit();

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient
        ]);
    }

    public function get(Patient $patient)
    {
        return new PatientAccessResource($patient);
    }
}
