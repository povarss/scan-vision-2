<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReferenceController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/reference-by-key', [ReferenceController::class, 'getByKey']);

    Route::prefix('patient')->group(function () {
        Route::get('/', [PatientController::class, 'list']);
        Route::post('/', [PatientController::class, 'store']);
        Route::post('/archive', [PatientController::class, 'archive']);
        Route::get('/{patient}', [PatientController::class, 'get']);
    });

    Route::prefix('exam')->group(function () {
        Route::post('/setting', [ExamController::class, 'storeSettings']);
        Route::post('/result', [ExamController::class, 'storeResult']);
        Route::get('/info/{patientExam}', [ExamController::class, 'getInfo']);
        Route::get('/test-pattern/{patientExam}', [ExamController::class, 'getTestPattern']);
        Route::get('/detail/{patientExam}', [ExamController::class, 'getDetail']);
        Route::get('/print/{patientExam}', [ExamController::class, 'print']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('doctor')->group(function () {
            Route::get('/', [DoctorController::class, 'list']);
            Route::post('/', [DoctorController::class, 'store']);
            Route::get('/{user}', [DoctorController::class, 'get']);
        });
    });
});
