<?php

namespace App\Http\Controllers;

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
        $result = $this->metricQueryService->createMetricQuery(
            $request->validated()['prompt'],
            $request->validated()['display_type'],
            $request->user()
        );

        return $this->successResponse(
            $result,
            'Consulta ejecutada exitosamente',
            200
        );
    }
}
