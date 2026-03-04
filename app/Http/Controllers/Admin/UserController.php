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

    public function __construct(
        protected readonly UserService $userService
    ) {}

    public function index(): JsonResponse
    {
        $users = $this->userService->showAll();

        return response()->json($users);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $this->userService->createOrUpdateUser($request->validated());

        return $this->successResponse(
            null,
            'User created successfully',
            201
        );
    }

    public function update(User $user, UserRequest $request): JsonResponse
    {
        $user_data = array_merge(
            $request->validated(),
            ['id' => $user->id]
        );

        $this->userService->createOrUpdateUser($user_data);

        return $this->successResponse(
            null,
            'User updated successfully',
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user->id);

        return $this->successResponse(
            null,
            'User deleted successfully',
        );
    }
}