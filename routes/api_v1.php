<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use app\Http\Controllers\Api\V1\MyController; // แบบนี้ผิด ต้องใช้ App\... A ตัวใหญ่
use App\Http\Controllers\Api\V1\MyController;
use App\Http\Controllers\Api\V1\CrudController;

// Protected route group
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('mytest', [MyController::class, 'index']);
});
