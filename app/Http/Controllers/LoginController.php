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

    public function __construct() {
        $this->login_service = new LoginService();
    }

    public function sessionLogin(LoginRequest $request) : JsonResponse
    {
        $response = $this->login_service->generateSession($request);

        return $this->successResponse($response);
    }

    public function logoutSession(Request $request) : JsonResponse
    {
        $this->login_service->destroySession($request);
        
        return $this->successResponse();
    }

    public function createToken(LoginRequest $request) : JsonResponse
    {
        $response = $this->login_service->generateToken($request);

        return $this->successResponse($response);
    }

    public function revokeToken(Request $request) : JsonResponse
    {
        $this->login_service->revokeToken($request);
        
        return $this->successResponse();
    }

    public function userAuthenticate() : JsonResponse
    {
        return $this->successResponse(null, 'User authenticated successfully');
    }
}