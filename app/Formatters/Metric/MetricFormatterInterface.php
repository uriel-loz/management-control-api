<?php

namespace App\Formatters\Metric;

interface MetricFormatterInterface
{
    /**
     * Format query results for display.
     *
     * @param  array  $results  Raw query results
     * @param  string  $prompt  Natural language prompt used for the query
     * @param  array  $config  Optional configuration parameters
     * @return array Formatted output specific to the formatter type
     */
    public function format(array $results, string $prompt, array $config = []): array;

    /**
     * Get the formatter type identifier.
     */
    public function type(): string;
}
