<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly ProductService $productService
    ) {}

    public function index(): JsonResponse
    {
        $products = $this->productService->showAll();

        return $this->successResponse($products);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return $this->successResponse($product, 'Product created successfully', 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product = $this->productService->showOne($product);

        return $this->successResponse($product);
    }

    public function update(Product $product, ProductRequest $request): JsonResponse
    {
        $product = $this->productService->update($product, $request->validated());

        return $this->successResponse($product, 'Product updated successfully');
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return $this->successResponse(null, 'Product deleted successfully');
    }
}
