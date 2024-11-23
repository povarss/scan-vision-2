<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

// Route::get('/test-pattern/{patientExam}', [ExamController::class, 'checkPattern']);
// Route::get('/print/{patientExam}', [ExamController::class, 'print']);
Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
