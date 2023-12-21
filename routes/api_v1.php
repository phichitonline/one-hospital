<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\MyController;
use App\Http\Controllers\Api\V1\OpdController;
use App\Http\Controllers\Api\V1\PersonController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\ExchangeController;
use App\Http\Controllers\Api\V1\DashboardController;

// Protected route group
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('mytest', [MyController::class, 'index']);
    Route::get('patient', [PatientController::class, 'patient']);
    Route::get('person', [PersonController::class, 'person']);
    Route::get('count_visit_lastmonth', [DashboardController::class, 'count_visit_lastmonth']);

    Route::prefix('exchange')->group(function () {
        Route::get('doctor', [ExchangeController::class, 'doctor']);
        Route::get('drugitems', [ExchangeController::class, 'drugitems']);
        Route::get('drugusage', [ExchangeController::class, 'drugusage']);
        Route::get('kskdepartment', [ExchangeController::class, 'kskdepartment']);
        Route::get('opitemrece/{vn}', [ExchangeController::class, 'opitemrece']);
        Route::get('patient/{cid}', [ExchangeController::class, 'patient']);
        Route::get('person/{cid}', [ExchangeController::class, 'person']);
        Route::get('sp_use', [ExchangeController::class, 'sp_use']);
    });

    Route::prefix('opd')->group(function () {
        Route::get('visit', [OpdController::class, 'visit']);
    });



});
