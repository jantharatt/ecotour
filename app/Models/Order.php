<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'รอชำระเงิน',
        'paid' => 'แจ้งชำระแล้ว',
        'packing' => 'กำลังแพ็กสินค้า',
        'shipped' => 'จัดส่งแล้ว',
        'completed' => 'สำเร็จ',
        'cancelled' => 'ยกเลิก',
    ];

    public const PAYMENT_METHODS = [
        'promptpay' => 'พร้อมเพย์ (QR)',
        'transfer' => 'โอนผ่านธนาคาร',
        'cod' => 'เก็บเงินปลายทาง',
    ];

    protected $fillable = [
        'code', 'user_id', 'subtotal', 'shipping_fee', 'total', 'payment_method', 'payment_slip',
        'status', 'ship_name', 'ship_phone', 'ship_address', 'tracking_no',
    ];

    protected $casts = ['subtotal' => 'decimal:2', 'shipping_fee' => 'decimal:2', 'total' => 'decimal:2'];

    protected static function booted(): void
    {
        static::creating(fn (Order $o) => $o->code ??= 'OD'.now()->format('ymd').strtoupper(Str::random(4)));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
