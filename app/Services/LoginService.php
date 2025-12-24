<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginService {
    public function generateSessionToken(array $credentials) : array
    {
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Credenciales inválidas', 401);
        }

        $session_token = Str::random(60);
        
        Cache::put('session_token:' . $session_token, [
            'user_id' => Auth::id(),
            'email' => $credentials['email'],
            'expires_at' => now()->addMinutes(5)
        ], 300);

        return [
            'session_token' => $session_token,
            'redirect_to' => '/oauth/authorize?session_token=' . $session_token
        ];
    }

    public function processAuthorization(array $pkce_data, string $session_token) : array
    {
        if (!$session_token || empty($session_token)) 
            throw new \Exception('Token de sesión inválido', 400);

        $session_data = Cache::get('session_token:' . $session_token);
        
        if ($session_data) {
            Auth::loginUsingId($session_data['user_id']);
            
            Cache::forget('session_token:' . $session_token);
        }

        if (!Auth::check()) {
            return [
                'status' => 'redirect',
                'url' => '/',
                'message' => 'Debe iniciar sesión para continuar'
            ];
        }

        $code = $this->generateAuthorizationCode($pkce_data, Auth::user());

        return [
            'code' => $code,
            'state' => $pkce_data['state']
        ];
    }

    private function generateAuthorizationCode($pkce_data, $user): string {
        $code = Str::random(40);
        
        DB::table('oauth_auth_codes')->insert([
            'id' => hash('sha256', $code),
            'user_id' => $user->id,
            'client_id' => $pkce_data['client_id'],
            'scopes' => '[]',
            'revoked' => false,
            'expires_at' => now()->addMinutes(10),
            'code_challenge' => $pkce_data['code_challenge'],
            'code_challenge_method' => $pkce_data['code_challenge_method'],
        ]);
        
        $redirectUrl = $pkce_data['redirect_uri'] . '?code=' . $code;
        
        if (isset($pkce_data['state'])) {
            $redirectUrl .= '&state=' . $pkce_data['state'];
        }
        
        return $redirectUrl;
    }
}