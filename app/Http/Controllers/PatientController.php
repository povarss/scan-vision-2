<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientAccessResource;
use App\Http\Resources\PatientResource;
use App\Models\Exam;
use App\Models\Patient;
use App\Models\PatientExam;
use App\Models\Tag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    // Register a new user
    public function list(Request $request)
    {
        $model = Patient::query()
            ->where('doctor_id', Auth::id())
            ->where('is_archived', 0);

        if (!empty($request->q)) {
            $model->where(function (Builder $query) use ($request) {
                $query->whereLike('full_name', '%' . $request->q . '%')
                    ->orWhereLike('phone', '%' . $request->q . '%');
            });
        }
        $examTypes = Exam::get();
        return DataTables::eloquent($model)
            ->only(['id', 'full_name', 'phone', 'first_test', 'last_test'])
            ->addColumn('first_test', function (Patient $patient) use ($examTypes) {
                $title = '';
                foreach ($examTypes as $examType) {
                    $exam = PatientExam::where('patient_id', $patient->id)->where('exam_id', $examType->id)->orderBy('id', 'asc')->where('status', PatientExam::STATUS_FINISHED)->first();
                    $title  .= $examType->label . ' ' . (!empty($exam) ? '(' . $exam->getCorrectPercentage() . '%)' : '').', ';
                }
                return $title;
            })
            ->addColumn('last_test', function (Patient $patient) use ($examTypes) {
                $title = '';
                foreach ($examTypes as $examType) {
                    $exam = PatientExam::where('patient_id', $patient->id)->where('exam_id', $examType->id)->orderBy('id', 'desc')->where('status', PatientExam::STATUS_FINISHED)->first();
                    $title  .= $examType->label . ' ' . (!empty($exam) ? '(' . $exam->getCorrectPercentage() . '%)' : '').', ';
                }
                return $title;
            })
            ->toJson();
    }

    public function store(StorePatientRequest $request)
    {
        $fields = $request->validated();
        $fields['doctor_id'] = Auth::id();
        DB::beginTransaction();


        $patient = !empty($request->id) ? Patient::findOrFail($request->id) : new Patient();
        $patient->fill($fields);
        $patient->save();

        $tagIds  = [];
        if (!empty($request->tags)) {
            $storedTags = Tag::get()->keyBy('label');
            foreach ($request->tags as $tagLabel) {
                if ($storedTags->has($tagLabel)) {
                    $tagIds[] = $storedTags->get($tagLabel)->id;
                } else {
                    $newTag = Tag::create([
                        'label' => $tagLabel
                    ]);
                    $tagIds[] = $newTag->id;
                }
            }
        }
        $patient->tags()->sync($tagIds);
        DB::commit();



        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient
        ]);
    }

    public function archive(Request $request)
    {
        $patient = Patient::findOrFail($request->id);
        $patient->archive();
        return response()->noContent();
    }

    public function get(Patient $patient)
    {
        return new PatientResource($patient);
    }

    public function getAccess(Patient $patient)
    {
        return new PatientAccessResource($patient);
    }
}
