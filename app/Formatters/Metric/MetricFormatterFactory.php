<?php

namespace App\Formatters\Metric;

class MetricFormatterFactory
{
    /**
     * @var array<string, string>
     */
    private array $formatters = [
        'table' => TableFormatter::class,
        'chart' => ChartFormatter::class,
        'metric' => MetricFormatter::class,
    ];

    /**
     * Resolve formatter instances for the given display types.
     *
     * @param  array<string>  $displayTypes  Array of display type strings
     * @return array<MetricFormatterInterface>
     *
     * @throws \Exception
     */
    public function resolve(array $displayTypes): array
    {
        return array_map(function (string $type) {
            if (! isset($this->formatters[$type])) {
                throw new \Exception("Formatter no soportado: {$type}", 400);
            }

            return new ($this->formatters[$type])();
        }, $displayTypes);
    }
}
