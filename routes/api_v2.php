<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\TestmyController;

// Protected route group
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('mytest', [TestmyController::class, 'index']);
});
