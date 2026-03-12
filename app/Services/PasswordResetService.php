<?php

namespace App\Services;

use App\Models\User;
use App\Traits\SendMailTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    use SendMailTrait;

    /**
     * Genera y envía un enlace de recuperación de contraseña.
     *
     * La respuesta al cliente siempre es genérica: no se revela si el
     * email existe o no en la base de datos (previene enumeración de usuarios).
     */
    public function sendResetLink(string $email): void
    {
        $user = User::where('email', $email)->first();

        // Si el usuario no existe, retornamos silenciosamente.
        // El controlador siempre responderá con éxito al cliente.
        if (!$user) {
            return;
        }

        // Eliminar tokens anteriores del mismo email
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // Generar token seguro
        $token = Str::random(64);

        // Guardar el token hasheado en la tabla
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl   = $this->buildResetUrl($token, $email);
        $expiresIn  = config('auth.passwords.users.expire', 60);

        $this->sendMail(
            to: $email,
            subject: 'Recuperación de contraseña — Management Control System',
            template: 'emails.reset-password',
            data: [
                'user_name'  => $user->name,
                'reset_url'  => $resetUrl,
                'expires_in' => $expiresIn,
            ]
        );
    }

    /**
     * Construye la URL del frontend Angular con el token y email como query params.
     */
    private function buildResetUrl(string $token, string $email): string
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:4200');

        return "{$frontendUrl}/reset-password?" . http_build_query([
            'token' => $token,
            'email' => $email,
        ]);
    }
}
