<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Services\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    use ApiResponseTrait;

    protected $role_service;

    public function __construct() {
        $this->role_service = new RoleService();
    }

    public function index() : JsonResponse
    {
        $roles = $this->role_service->showAll();
        return response()->json($roles);
    }

    public function store(RoleRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $this->role_service->createOrUpdateRole($data);

        return $this->successResponse(
            null,
            'Role created',
            201
        );
    }

    public function update(RoleRequest $request, Role $role) : JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $role->id]);
        $this->role_service->createOrUpdateRole($data);

        return $this->successResponse(
            null,
            'Role updated',
            200
        );
    }

    public function destroy(Role $role) : JsonResponse
    {
        $this->role_service->deleteRole($role->id);

        return $this->successResponse(
            null,
            'Role deleted',
            200
        );
    }
}

