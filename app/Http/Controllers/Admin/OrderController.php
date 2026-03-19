<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly OrderService $orderService
    ) {}

    public function index(): JsonResponse
    {
        $orders = $this->orderService->showAll();

        return response()->json($orders);
    }

    public function show(Order $order): JsonResponse
    {
        return $this->successResponse($this->orderService->find($order->id));
    }

    public function cancel(Order $order): JsonResponse
    {
        $this->orderService->cancel($order);

        return $this->successResponse(null, 'Order cancelled successfully');
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->orderService->delete($order);

        return $this->successResponse(null, 'Order deleted successfully');
    }
}
