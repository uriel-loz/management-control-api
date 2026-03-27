<?php

namespace App\Enums;

enum MetricQuerySource: string
{
    case LLM = 'llm';
    case TEMPLATE = 'template';

    public function label(): string
    {
        return match($this) {
            self::LLM => 'LLM',
            self::TEMPLATE => 'Template',
        };
    }
}
