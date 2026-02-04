<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'sessionLogin']);
        Route::post('token', [LoginController::class, 'createToken']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [LoginController::class, 'logoutSession']);
        Route::delete('auth/token', [LoginController::class, 'revokeToken']);

        Route::prefix('admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('roles', RoleController::class);
        });
    });
});