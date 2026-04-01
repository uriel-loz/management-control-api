<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMetricQueryRequest;
use App\Services\MetricQueryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class MetricQueryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected readonly MetricQueryService $metricQueryService
    ) {}

    /**
     * Generate and execute a metric query from natural language.
     */
    public function query(CreateMetricQueryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->metricQueryService->createMetricQuery(
            $validated['prompt'],
            $validated['display_type'],
            $request->user(),
            $validated['display_config'] ?? []
        );

        return $this->successResponse(
            $result,
            'Consulta ejecutada exitosamente',
            200
        );
    }
}
