<?php

use App\Http\Controllers\User\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserAuthController;

Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth.user')->group(function () {
        Route::get('me', [UserAuthController::class, 'me']);
        Route::resource('tasks', TaskController::class);
    });
});