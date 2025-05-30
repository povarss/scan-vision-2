<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PatientAccessController;
use App\Http\Controllers\PatientAllController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
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

    Route::get('/user-access', [UserController::class, 'getAccess']);

    Route::prefix('patient')->controller(PatientController::class)->group(function () {
        Route::group(['middleware' => ['role:admin|doctor']], function () {

            Route::get('/',  'list');
            Route::post('/',  'store');
            Route::post('/archive',  'archive');
        });
        Route::get('/{patient}',  'get');
    });

    Route::post('/promo/activate', [PromoCodeController::class, 'activate']);

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

        Route::prefix('patient-access')->controller(PatientAccessController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('/{patient}', 'get');
        });
    });
});
