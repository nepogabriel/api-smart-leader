<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::middleware('jwt')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);