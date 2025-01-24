<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PatientAllController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/checkFirst', [RegisterController::class, 'checkFirst']);
Route::post('/checkSecond', [RegisterController::class, 'checkSecond']);
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/reference-by-key-guest', [ReferenceController::class, 'getByKeyGuest']);

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
        Route::get('/refrences/{type}', [ExamController::class, 'getReferences']);
        Route::post('/make-draft', [ExamController::class, 'makeDraft']);
        Route::post('/setting', [ExamController::class, 'storeSettings']);
        Route::post('/result', [ExamController::class, 'storeResult']);
        Route::get('/info/{patientExam}', [ExamController::class, 'getInfo']);
        Route::get('/test-pattern/{patientExam}', [ExamController::class, 'getTestPattern']);
        Route::get('/detail/{patientExam}', [ExamController::class, 'getDetail']);
        Route::get('/print/{patientExam}', [ExamController::class, 'print']);

        Route::post('/set-time/{patientExam}', [ExamController::class, 'setTime']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('doctor')->group(function () {
            Route::get('/', [DoctorController::class, 'list']);
            Route::post('/', [DoctorController::class, 'store']);
            Route::get('/{user}', [DoctorController::class, 'get']);
            Route::post('/delete', [DoctorController::class, 'delete']);
        });

        Route::prefix('promo-code')->controller(PromoCodeController::class)->group(function () {
            Route::get('/',  'list');
            Route::post('/',  'store');
            Route::get('/{promoCode}',  'get');
            Route::post('/delete',  'delete');
        });

        Route::prefix('patient-all')->group(function () {
            Route::get('/', [PatientAllController::class, 'list']);
            Route::post('/', [PatientAllController::class, 'store']);
            Route::post('/archive', [PatientAllController::class, 'archive']);
            Route::get('/{patient}', [PatientAllController::class, 'get']);
        });
    });
});
