<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Services\ImageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly ImageService $imageService
    ) {}

    public function store(ImageRequest $request): JsonResponse
    {
        $this->imageService->upload($request->validated());

        return $this->successResponse(null, 'Imágenes subidas correctamente.', 201);
    }
}
