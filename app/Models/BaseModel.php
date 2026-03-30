<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

abstract class BaseModel extends Model {
    use SoftDeletes, HasUuids;

    protected $baseHidden = ['deleted_at'];
    public $incrementing = false;

    protected $baseCasts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->hidden = array_merge($this->baseHidden, $this->hidden);
        $this->mergeCasts($this->baseCasts);
    }
}