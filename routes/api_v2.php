<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\MyController;

// Protected route group
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('mytest', [MyController::class, 'index']);
});
