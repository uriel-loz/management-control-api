<?php

namespace App\Services;

use App\Models\MetricQuery;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MetricQueryService
{
    /**
     * Create a new instance of the service.
     */
    public function __construct(
        protected readonly TextToSqlService $textToSqlService
    ) {}

    /**
     * Create a metric query from natural language prompt.
     *
     * @param string $prompt Natural language query
     * @param string $displayType Display type: table, chart, metric
     * @param User $user Authenticated user
     * @return array Formatted query results
     * @throws \Exception
     */
    public function createMetricQuery(string $prompt, string $displayType, User $user): array
    {
        // Step 1: Create temporary bearer token for microservice
        $tokenInstance = $user->createToken('microservice');
        $bearerToken = $tokenInstance->plainTextToken;

        // Step 2: Call microservice to generate SQL
        $microserviceResponse = $this->textToSqlService->generateQuery(
            $prompt,
            $displayType,
            $bearerToken
        );

        // Step 3: Delete the temporary token (already served its purpose)
        $tokenInstance->accessToken->delete();

        $token = $microserviceResponse['token'];

        // Step 4: Retrieve the stored MetricQuery by token
        $metricQuery = MetricQuery::where('token', $token)->first();

        if (!$metricQuery) {
            throw new \Exception('No se encontró la consulta generada.', 404);
        }

        // Step 5: Execute the generated SQL
        $results = DB::select($metricQuery->generated_sql);

        // Convert stdClass objects to arrays
        $results = array_map(fn($row) => (array) $row, $results);

        // Step 6: Format results based on display_type
        $formattedData = match ($displayType) {
            'chart' => $this->formatChartData($results, $prompt),
            'table' => $this->formatTableData($results),
            'metric' => $this->formatMetricData($results, $prompt),
            default => throw new \Exception('Tipo de visualización no válido.', 400),
        };

        // Step 7: Return complete response
        return [
            'token' => $token,
            'prompt' => $prompt,
            'display_type' => $displayType,
            'source' => $microserviceResponse['source'],
            'data' => $formattedData,
        ];
    }

    /**
     * Format results for chart display.
     *
     * Strategy: Detect first column as labels, second column as values
     * If datetime/date column exists, use it as labels
     * Otherwise use first column
     */
    private function formatChartData(array $results, string $prompt): array
    {
        if (empty($results)) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        $firstRow = $results[0];
        $columns = array_keys($firstRow);

        // Detect label column (prefer date/time columns)
        $labelColumn = $columns[0];
        foreach ($columns as $column) {
            $value = $firstRow[$column];
            if (preg_match('/\d{4}-\d{2}-\d{2}/', $value)) {
                $labelColumn = $column;
                break;
            }
        }

        // Detect value column (first numeric column that's not the label)
        $valueColumn = null;
        foreach ($columns as $column) {
            if ($column === $labelColumn) continue;
            if (is_numeric($firstRow[$column])) {
                $valueColumn = $column;
                break;
            }
        }

        if (!$valueColumn) {
            $valueColumn = $columns[1] ?? $columns[0];
        }

        // Extract labels and data
        $labels = array_map(fn($row) => $row[$labelColumn], $results);
        $data = array_map(fn($row) => (float) $row[$valueColumn], $results);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => ucfirst($valueColumn),
                    'data' => $data,
                ]
            ],
        ];
    }

    /**
     * Format results for table display.
     */
    private function formatTableData(array $results): array
    {
        if (empty($results)) {
            return [
                'columns' => [],
                'rows' => [],
            ];
        }

        $columns = array_keys($results[0]);

        return [
            'columns' => $columns,
            'rows' => $results,
        ];
    }

    /**
     * Format results for metric display.
     *
     * Strategy: Return first row, first column as single value
     */
    private function formatMetricData(array $results, string $prompt): array
    {
        if (empty($results)) {
            return [
                'value' => 0,
                'label' => 'Sin resultados',
            ];
        }

        $firstRow = $results[0];
        $firstColumn = array_key_first($firstRow);
        $value = $firstRow[$firstColumn];

        // Generate label from column name or prompt
        $label = ucfirst(str_replace('_', ' ', $firstColumn));

        return [
            'value' => $value,
            'label' => $label,
        ];
    }
}
