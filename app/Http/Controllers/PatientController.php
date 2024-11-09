<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Reference;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    // Register a new user
    public function list(Request $request, Reference $reference)
    {
        $model = Patient::query();

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
