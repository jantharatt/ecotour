<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description', 'conservation', 'address', 'subdistrict', 'district',
        'province', 'lat', 'lng', 'contact_name', 'contact_phone', 'facebook_url', 'line_id',
        'cover_image', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'lat' => 'float', 'lng' => 'float'];

    public function attractions(): HasMany
    {
        return $this->hasMany(Attraction::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getLocationTextAttribute(): string
    {
        return collect([
            $this->subdistrict ? 'ต.'.$this->subdistrict : null,
            $this->district ? 'อ.'.$this->district : null,
            $this->province ? 'จ.'.$this->province : null,
        ])->filter()->implode(' ');
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/'.$this->cover_image)
            : asset('images/placeholders/ayutthaya.svg');
    }

    public function getDirectionsUrlAttribute(): ?string
    {
        return ($this->lat && $this->lng)
            ? "https://www.google.com/maps/dir/?api=1&destination={$this->lat},{$this->lng}"
            : null;
    }
}
