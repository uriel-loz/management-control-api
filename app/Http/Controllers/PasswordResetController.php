<?php

namespace App\Http\Controllers;

use App\Services\PasswordResetService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ForgotPasswordRequest;

class PasswordResetController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly PasswordResetService $passwordResetService
    ) {}

    /**
     * Procesa la solicitud de recuperación de contraseña.
     *
     * La respuesta siempre es 200 (éxito genérico) independientemente
     * de si el email existe en la base de datos o no.
     * Esto previene la enumeración de usuarios registrados.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->passwordResetService->sendResetLink($request->input('email'));

        return $this->successResponse(
            null,
            'Si el correo está registrado, recibirás las instrucciones de recuperación en tu bandeja de entrada.'
        );
    }
}
