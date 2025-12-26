<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('auth/session-login', [LoginController::class, 'sessionLogin']);
