<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ModuleController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'sessionLogin'])
            ->middleware('throttle:5,1');
        Route::post('token', [LoginController::class, 'createToken'])
            ->middleware('throttle:5,1');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [LoginController::class, 'logoutSession']);
        Route::get('auth/check', [LoginController::class, 'userAuthenticate']);
        Route::delete('auth/revoke-token', [LoginController::class, 'revokeToken']);

        Route::prefix('admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('roles', RoleController::class);

            // Modules routes
            Route::get('modules', [ModuleController::class, 'index']);
            Route::get('modules/user', [ModuleController::class, 'getModulesByUser']);
        });
    });
});