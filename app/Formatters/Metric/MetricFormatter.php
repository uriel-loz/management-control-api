<?php

namespace App\Formatters\Metric;

class MetricFormatter implements MetricFormatterInterface
{
    /**
     * Format results for metric display.
     *
     * Strategy: Return first row, first column as single value.
     *
     * @param  array  $results  Raw query results
     * @param  string  $prompt  Natural language prompt (unused for metric format)
     * @param  array  $config  Optional configuration parameters
     * @return array{value: mixed, label: string}
     */
    public function format(array $results, string $prompt, array $config = []): array
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

        // Generate label from column name
        $label = ucfirst(str_replace('_', ' ', $firstColumn));

        return [
            'value' => $value,
            'label' => $label,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function type(): string
    {
        return 'metric';
    }
}
