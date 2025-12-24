<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('session-login', [LoginController::class, 'sessionLogin']);
    });

    Route::get('/oauth/authorize', [LoginController::class, 'authorize']);
});