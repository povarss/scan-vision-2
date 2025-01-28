<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\UserResource;
use App\Models\Answer;
use App\Models\Patient;
use App\Models\PatientAnswer;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\User;
use App\Services\CheckTestAccessService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PatientAllController extends Controller
{
    // Register a new user
    public function list(Request $request)
    {
        $model = Patient::query()->with(['user', 'patient_answers'])
            ->where('is_archived', 0);

        if (!empty($request->q)) {
            $model->where(function (Builder $query) use ($request) {
                $query->whereLike('full_name', '%' . $request->q . '%')
                    ->orWhereLike('field', '%' . $request->q . '%')
                    ->orWhereHas('user', function ($query) use ($request) {
                        return $query->whereLike('email', '%' . $request->q . '%');
                    })
                    ->orWhereLike('nick_name', '%' . $request->q . '%');
            });
        }

        $questions = Question::get()->keyBy('id');
        $answers = Answer::get()->keyBy('id');

        return DataTables::eloquent($model)
            ->only(['id', 'expire_at', 'doctor', 'email', 'full_name', 'answers', 'comment'])
            ->editColumn('full_name', function (Patient $patient) {
                return $patient->detail_full_name;
            })
            ->editColumn('doctor', function (Patient $patient) {
                return !empty($patient->doctor_id) ? $patient->doctor?->name : '';
            })
            ->editColumn('email', function (Patient $patient) {
                return !empty($patient->user_id) ? $patient->user?->email : '';
            })
            ->editColumn('comment', function (Patient $patient) {
                return $patient->field;
            })
            ->addColumn('expire_at', function (Patient $patient) {
                if (empty($patient->user_id)) {
                    return  null;
                }
                $access = (new CheckTestAccessService($patient->user))->handle();
                return !empty($access) ? Carbon::parse($access->endDate)->format('d.m.Y') : '';
            })
            ->addColumn('answers', function (Patient $patient) use ($questions, $answers) {
                if (empty($patient->patient_answers)) {
                    return  null;
                }
                $formattedAnswers = $patient->patient_answers->map(function (PatientAnswer $patientAnswer) use ($questions, $answers) {
                    return $questions->get($patientAnswer->question_id)?->content . ' ' . $answers->get($patientAnswer->answer_id)->content;
                });
                return $formattedAnswers->implode('<br>');
            })
            ->rawColumns(['answers'])
            ->toJson();
    }

    public function store(StoreDoctorRequest $request)
    {
        $fields = $request->validated();
        DB::beginTransaction();


        $user = !empty($request->id) ? User::findOrFail($request->id) : new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->expire_at = $request->expire_at;
        $user->save();

        Subscription::store($user, $request->expire_at, $request->minutes);

        $user->assignRole(User::ROLE_DOCTOR);
        DB::commit();



        return response()->json([
            'message' => 'User saved successfully',
            'user' => $user
        ]);
    }

    public function get(User $user)
    {
        return new UserResource($user);
    }

    public function delete(Request $request)
    {
        $doctor = User::findOrFail($request->input('id'));
        $doctor->remove();
    }
}
