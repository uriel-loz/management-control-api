<?php

namespace App\Http\Controllers\Admin;

use App\Services\ModuleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    use ApiResponseTrait;

    protected $module_service;

    public function __construct()
    {
        $this->module_service = new ModuleService();
    }

    public function index(): JsonResponse
    {
        $modules = $this->module_service->showAll();
        return $this->successResponse($modules);
    }
}
