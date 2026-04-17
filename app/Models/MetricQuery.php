<?php

namespace App\Models;

use App\Enums\MetricQuerySource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricQuery extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'token';

    protected $fillable = [
        'token',
        'user_id',
        'prompt',
        'generated_sql',
        'structure',
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
            'structure' => 'array',
            'source' => MetricQuerySource::class,
            'is_saved' => 'boolean',
            'is_pinned' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
