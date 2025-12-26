<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginService {
    public function generateSession(LoginRequest $request) : void
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) 
            throw new \Exception('Credenciales inválidas', 401);
        
        $request->session()->regenerate();
    }
}