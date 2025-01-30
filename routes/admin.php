<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\TaskController;

Route::prefix('admin')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth.admin')->group(function () {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::resource('tasks', TaskController::class);

    });
});