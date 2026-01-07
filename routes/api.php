<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::prefix('v1')->group(function () {
    Route::post('auth/login', [LoginController::class, 'sessionLogin']);
    Route::post('auth/token', [LoginController::class, 'createToken']);
});