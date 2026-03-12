<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly CategoryService $categoryService
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->showAll();

        return $this->successResponse($categories);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return $this->successResponse($category, 'Category created successfully', 201);
    }

    public function show(Category $category): JsonResponse
    {
        $category = $this->categoryService->showOne($category);

        return $this->successResponse($category);
    }

    public function update(Category $category, CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->update($category, $request->validated());

        return $this->successResponse($category, 'Category updated successfully');
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return $this->successResponse(null, 'Category deleted successfully');
    }
}
