<?php

namespace App\Services;

use App\Formatters\Metric\MetricFormatterFactory;
use App\Models\MetricQuery;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MetricQueryService
{
    /**
     * Create a new instance of the service.
     */
    public function __construct(
        protected readonly TextToSqlService $textToSqlService,
        protected readonly MetricFormatterFactory $formatterFactory
    ) {}

    /**
     * Create a metric query from natural language prompt.
     *
     * @param  string  $prompt  Natural language query
     * @param  array<string>  $displayTypes  Display types: table, chart, metric
     * @param  User  $user  Authenticated user
     * @param  array<string, array>  $displayConfig  Per-format configuration options
     * @return array Formatted query results
     *
     * @throws \Exception
     */
    public function createMetricQuery(string $prompt, array $displayTypes, User $user, array $displayConfig = []): array
    {
        // Step 1: Create temporary bearer token for microservice
        $tokenInstance = $user->createToken('microservice');
        $bearerToken = $tokenInstance->plainTextToken;

        // Step 2: Call microservice to generate SQL
        $microserviceResponse = $this->textToSqlService->generateQuery(
            $prompt,
            $bearerToken
        );

        // Step 3: Delete the temporary token (already served its purpose)
        $tokenInstance->accessToken->delete();

        $token = $microserviceResponse['token'];

        // Step 4: Retrieve the stored MetricQuery by token
        $metricQuery = MetricQuery::where('token', $token)->first();

        if (! $metricQuery) {
            throw new \Exception('No se encontró la consulta generada.', 404);
        }

        // Step 5: Execute the generated SQL
        $results = DB::select($metricQuery->generated_sql);

        // Convert stdClass objects to arrays
        $results = array_map(fn ($row) => (array) $row, $results);

        // Step 6: Resolve formatters via factory and apply each
        $formatters = $this->formatterFactory->resolve($displayTypes);

        $data = [];
        foreach ($formatters as $formatter) {
            $config = $displayConfig[$formatter->type()] ?? [];
            $data[$formatter->type()] = $formatter->format($results, $prompt, $config);
        }

        // Step 7: Return complete response
        return [
            'token' => $token,
            'prompt' => $prompt,
            'display_types' => $displayTypes,
            'source' => $microserviceResponse['source'],
            'data' => $data,
        ];
    }
}
