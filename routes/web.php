<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

Route::get('/print/{patientExam}', [ExamController::class, 'getTestPattern']);
Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
