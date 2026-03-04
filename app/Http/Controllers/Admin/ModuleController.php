<?php

namespace App\Http\Controllers\Admin;

use App\Services\ModuleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly ModuleService $moduleService
    ) {}

    public function index(): JsonResponse
    {
        $modules = $this->module_service->showAll();
        return $this->successResponse($modules);
    }

    public function getModulesByUser(): JsonResponse
    {
        $modules = $this->module_service->showModulesByRole();
        return $this->successResponse($modules);
    }
}
