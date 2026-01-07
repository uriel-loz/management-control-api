<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService {
    public function generateSession(LoginRequest $request) : array
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) 
            throw new \Exception('Credenciales inválidas', 401);
        
        $request->session()->regenerate();

        return [
            'user' => auth()->user(),
        ];
    }

    public function destroySession(Request $request) : void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function generateToken(LoginRequest $request) : array
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) 
            throw new \Exception('Credenciales inválidas', 401);

        return [
            'token' => $user->createToken($request->device)->plainTextToken,
            'user' => $user
        ];
    }

    public function revokeToken(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function revokeAllTokens(Request $request): void
    {
        $request->user()->tokens()->delete();
    }
}