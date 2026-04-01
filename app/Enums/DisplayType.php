<?php

namespace App\Enums;

enum DisplayType: string
{
    case TABLE = 'table';
    case CHART = 'chart';
    case METRIC = 'metric';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
