<?php

use App\Modules\AuthModule\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/auth')->name('api.auth.')->group(function () {
    Route::post('login', [TokenController::class, 'login'])
        ->middleware('throttle:auth-attempts')
        ->name('login');

    Route::post('register', [TokenController::class, 'register'])
        ->middleware('throttle:auth-attempts')
        ->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [TokenController::class, 'me'])->name('me');
        Route::post('logout', [TokenController::class, 'logout'])->name('logout');
    });
});
