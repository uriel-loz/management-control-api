<?php

namespace App\Formatters\Metric;

class ChartFormatter implements MetricFormatterInterface
{
    /**
     * Format results for chart display.
     *
     * Strategy: Detect first column as labels, second column as values.
     * If datetime/date column exists, use it as labels.
     * Otherwise use first column.
     *
     * @param  array  $results  Raw query results
     * @param  string  $prompt  Natural language prompt (unused for chart format)
     * @param  array  $config  Optional configuration parameters
     * @return array{labels: array, datasets: array}
     */
    public function format(array $results, string $prompt, array $config = []): array
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
            if ($column === $labelColumn) {
                continue;
            }
            if (is_numeric($firstRow[$column])) {
                $valueColumn = $column;
                break;
            }
        }

        if (! $valueColumn) {
            $valueColumn = $columns[1] ?? $columns[0];
        }

        // Extract labels and data
        $labels = array_map(fn ($row) => $row[$labelColumn], $results);
        $data = array_map(fn ($row) => (float) $row[$valueColumn], $results);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => ucfirst($valueColumn),
                    'data' => $data,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function type(): string
    {
        return 'chart';
    }
}
