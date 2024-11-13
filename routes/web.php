<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [ExamController::class, 'index']);

Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
