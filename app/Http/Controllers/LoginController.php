<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\LoginService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PKCERequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    use ApiResponseTrait;

    protected $login_service;

    public function __construct(LoginService $login_service) {
        $this->login_service = $login_service;
    }

    public function sessionLogin(LoginRequest $request) : JsonResponse
    {
        $this->login_service->generateSession($request);

        return $this->successResponse([
            'user' => auth()->user(),
        ]);
    }
}