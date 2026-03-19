<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected readonly PaymentService $paymentService
    ) {}

    public function index(): JsonResponse
    {
        $payments = $this->paymentService->showAll();

        return response()->json($payments);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->paymentService->delete($payment);

        return $this->successResponse(null, 'Payment deleted successfully');
    }
}
