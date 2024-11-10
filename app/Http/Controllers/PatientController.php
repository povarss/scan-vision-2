<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
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
        $model = Patient::query();

        if (!empty($request->q)) {
            $model->where(function (Builder $query) use ($request) {
                $query->whereLike('full_name', '%' . $request->q . '%')
                    ->orWhereLike('phone', '%' . $request->q . '%');
            });
        }
        return DataTables::eloquent($model)
            ->only(['id', 'full_name', 'phone', 'first_test', 'last_test'])
            ->addColumn('first_test', function (Patient $patient) {
                return 'Неглет тест (20%)';
            })
            ->addColumn('last_test', function (Patient $patient) {
                return 'Неглет тест (70%) ';
            })
            ->toJson();
    }

    public function store(StorePatientRequest $request,)
    {
        $fields = $request->validated();
        $fields['doctor_id'] = Auth::id();
        DB::beginTransaction();


        $patient = Patient::create($fields);

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
        ], 201);
    }
}
