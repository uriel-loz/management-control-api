<?php

namespace App\Enums;

enum DisplayType: string
{
    case TABLE = 'table';
    case CHART = 'chart';
    case METRIC = 'metric';

    public function label(): string
    {
        return match($this) {
            self::TABLE => 'Table',
            self::CHART => 'Chart',
            self::METRIC => 'Metric',
        };
    }
}
