<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attraction extends Model
{
    /** ประเภท => [ชื่อไทย, ไอคอน, สีหมุดบนแผนที่] */
    public const TYPES = [
        'nature' => ['ธรรมชาติ', '🌳', '#2F9660'],
        'viewpoint' => ['จุดชมวิว', '🏞️', '#1E86A3'],
        'culture' => ['วัฒนธรรม/ศาสนสถาน', '🛕', '#B4561A'],
        'learning' => ['แหล่งเรียนรู้', '🧺', '#8B5CF6'],
        'food' => ['ร้านอาหารถิ่น', '🍲', '#F28C28'],
        'homestay' => ['ที่พัก/โฮมสเตย์', '🏡', '#DB2777'],
    ];

    /** เส้นทาง 3 ศาสนา => [ชื่อ, ไอคอน, สี] */
    public const FAITHS = [
        'buddhism' => ['พุทธ', '🛕', '#D97706'],
        'christianity' => ['คริสต์', '⛪', '#2563EB'],
        'islam' => ['อิสลาม', '🕌', '#059669'],
    ];

    protected $fillable = [
        'community_id', 'name', 'slug', 'type', 'faith', 'summary', 'description', 'address', 'lat', 'lng',
        'opening_hours', 'entry_fee', 'best_time', 'contact_phone', 'eco_tips', 'image', 'gallery',
        'is_featured', 'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    /**
     * เพิ่มคอลัมน์ distance_km (สูตร Haversine) และเรียงจากใกล้ไปไกล — ใช้กับ MySQL
     */
    public function scopeNearest(Builder $q, float $lat, float $lng): Builder
    {
        return $q->whereNotNull('lat')->whereNotNull('lng')
            ->selectRaw('attractions.*, (6371 * ACOS(LEAST(1, COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat))))) AS distance_km', [$lat, $lng, $lat])
            ->orderBy('distance_km');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type][0] ?? $this->type;
    }

    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type][1] ?? '📍';
    }

    public function getFaithLabelAttribute(): ?string
    {
        return $this->faith ? self::FAITHS[$this->faith][0] : null;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : asset('images/placeholders/ayutthaya.svg');
    }

    public function getDirectionsUrlAttribute(): ?string
    {
        return ($this->lat && $this->lng)
            ? "https://www.google.com/maps/dir/?api=1&destination={$this->lat},{$this->lng}"
            : null;
    }

    /** ข้อมูลหมุดสำหรับส่งให้แผนที่ (JSON) */
    public function toMarker(): array
    {
        return [
            'id' => $this->id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'name' => $this->name,
            'type' => $this->type_label,
            'icon' => $this->faith ? self::FAITHS[$this->faith][1] : $this->type_icon,
            'color' => $this->faith ? self::FAITHS[$this->faith][2] : (self::TYPES[$this->type][2] ?? '#2F9660'),
            'faith' => $this->faith_label,
            'community' => $this->community?->name,
            'url' => route('places.show', $this),
            'directions' => $this->directions_url,
        ];
    }
}
