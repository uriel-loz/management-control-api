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

        $metricQuery->update([
            'display_type' => $displayTypes
        ]);

        // Step 5–6: Execute SQL and format results
        $data = $this->executeAndFormat($metricQuery, $displayTypes, $displayConfig);

        // Step 7: Return complete response
        return [
            'token'         => $token,
            'prompt'        => $prompt,
            'display_types' => $displayTypes,
            'source'        => $microserviceResponse['source'],
            'data'          => $data,
        ];
    }

    /**
     * Get all saved (and/or pinned) metric queries for a user,
     * re-executing each SQL to build the formatted data payload.
     *
     * @param  User  $user
     * @return array<int, array>
     *
     * @throws \Exception
     */
    public function getSavedMetrics(User $user): array
    {
        $queries = MetricQuery::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('is_saved', true)
                  ->orWhere('is_pinned', true);
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return $queries->map(function (MetricQuery $metricQuery): array {
            $displayTypes = $metricQuery->display_type ?? [];
            $displayConfig = $metricQuery->display_config ?? [];

            return [
                'token'         => $metricQuery->token,
                'prompt'        => $metricQuery->prompt,
                'display_types' => $displayTypes,
                'source'        => $metricQuery->source,
                'is_saved'      => $metricQuery->is_saved,
                'is_pinned'     => $metricQuery->is_pinned,
                'data'          => $this->executeAndFormat($metricQuery, $displayTypes, $displayConfig),
            ];
        })->values()->all();
    }

    /**
     * Execute the stored SQL and apply formatters to build the data payload.
     *
     * @param  MetricQuery          $metricQuery
     * @param  array<string>        $displayTypes
     * @param  array<string, array> $displayConfig
     * @return array<string, array>
     */
    private function executeAndFormat(MetricQuery $metricQuery, array $displayTypes, array $displayConfig): array
    {
        $results = DB::select($metricQuery->generated_sql);
        $results = array_map(fn ($row) => (array) $row, $results);

        $data = [];
        foreach ($this->formatterFactory->resolve($displayTypes) as $formatter) {
            $config = $displayConfig[$formatter->type()] ?? [];
            $data[$formatter->type()] = $formatter->format($results, $metricQuery->prompt, $config);
        }

        return $data;
    }

    /**
     * Soft-delete a metric query that belongs to the given user.
     *
     * @param  string  $token
     * @param  User    $user
     * @return void
     *
     * @throws \Exception
     */
    public function deleteMetricQuery(string $token, User $user): void
    {
        $metricQuery = MetricQuery::where('token', $token)
            ->where('user_id', $user->id)
            ->first();

        if (! $metricQuery) {
            throw new \Exception('Métrica no encontrada.', 404);
        }

        $metricQuery->delete();
    }
}
