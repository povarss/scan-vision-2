<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Reference;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
}
