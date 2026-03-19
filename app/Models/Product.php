<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'quantity',
        'description',
    ];

    protected $hidden = [
        'pivot',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }
}
