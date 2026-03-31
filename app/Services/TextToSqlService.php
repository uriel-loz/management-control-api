<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TextToSqlService
{
    /**
     * Generate SQL query from natural language prompt via microservice.
     *
     * @param string $prompt Natural language query
     * @param string $displayType Display type: table, chart, metric
     * @param string $bearerToken User's Sanctum bearer token
     * @return array Microservice response: {token, display_type, source}
     * @throws \Exception
     */
    public function generateQuery(string $prompt, string $displayType, string $bearerToken): array
    {
        $response = Http::timeout(30)
            ->withToken($bearerToken)
            ->post(config('services.text_to_sql.url') . '/api/v1/query', [
                'prompt' => $prompt,
                'display_type' => $displayType,
            ]);

        // Handle rate limiting (429)
        if ($response->status() === 429) {
            $data = $response->json();
            throw new \Exception(
                'Límite de consultas alcanzado. Intenta nuevamente en ' .
                ($data['retryAfter'] ?? 'unos minutos'),
                429
            );
        }

        // Handle other errors
        if (!$response->successful()) {
            throw new \Exception(
                'Error al generar consulta SQL: ' . ($response->json()['error'] ?? 'Error desconocido'),
                $response->status()
            );
        }

        return $response->json();
    }
}
