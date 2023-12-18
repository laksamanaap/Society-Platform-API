<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthValidation;
use App\Http\Controllers\JobAppliesController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Authentication
Route::post('v1/auth/login', [AuthenticationController::class, 'loginUsers'])->name('loginUsers');
Route::post('v1/auth/logout', [AuthenticationController::class, 'logoutUsers'])->name('logoutUsers');

// Store Validation
Route::middleware(AuthValidation::class)->group(
    function () {
        Route::post('v1/validations', [ValidationController::class, 'storeValidation'])->name('storeValidation');
        Route::get('v1/job_vacancies', [ValidationController::class, 'getValidation'])->name('getValidation');
        Route::get('v1/job_vacancies/{id}', [ValidationController::class, 'getValidationById'])->name('getValidationById');

        Route::post('/v1/applications', [JobAppliesController::class, 'storeJobApply'])->name('storeJobApply');
        Route::get('/v1/applications', [JobAppliesController::class, 'getJobApply'])->name('getJobApply');

    }
);


