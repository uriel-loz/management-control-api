<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }

    public function index() : JsonResponse
    {
        $response = $this->user_service->showAll();

        return response()->json($response);
    }

    public function store(UserRequest $request) : JsonResponse
    {
        $this->user_service->createOrUpdateUser($request->validated());

        return $this->successResponse(
            null,
            'User created successfully',
            201
        );
    }

    public function update(User $user, UserRequest $request) : JsonResponse
    {
        $user_data = array_merge(
            $request->validated(),
            ['id' => $user->id]
        );

        $this->user_service->createOrUpdateUser($user_data);

        return $this->successResponse(
            null,
            'User updated successfully',
        );
    }

    public function destroy(User $user) : JsonResponse
    {
        $this->user_service->deleteUser($user->id);

        return $this->successResponse(
            null,
            'User deleted successfully',
        );
    }
}