<?php

namespace App\Formatters\Metric;

class TableFormatter implements MetricFormatterInterface
{
    /**
     * Format results for table display.
     *
     * @param  array  $results  Raw query results
     * @param  string  $prompt  Natural language prompt (unused for table format)
     * @param  array  $config  Optional configuration parameters
     * @return array{columns: array, rows: array}
     */
    public function format(array $results, string $prompt, array $config = []): array
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
     * {@inheritdoc}
     */
    public function type(): string
    {
        return 'table';
    }
}
