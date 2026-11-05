<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'community_id', 'product_category_id', 'name', 'slug', 'producer', 'story', 'description',
        'price', 'stock', 'unit', 'weight_gram', 'image', 'is_otop', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_otop' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : asset('images/placeholders/community-goods.svg');
    }
}
