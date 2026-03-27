<?php

namespace App\Models;

use App\Enums\DisplayType;
use App\Enums\MetricQuerySource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricQuery extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'prompt',
        'generated_sql',
        'display_type',
        'display_config',
        'source',
        'template_id',
        'is_saved',
        'is_pinned',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected function casts(): array
    {
        return [
            'display_type' => DisplayType::class,
            'source' => MetricQuerySource::class,
            'display_config' => 'array',
            'is_saved' => 'boolean',
            'is_pinned' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
