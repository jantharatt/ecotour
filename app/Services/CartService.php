<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * ตะกร้าสินค้าแบบเก็บใน Session: [product_id => qty]
 */
class CartService
{
    private const KEY = 'cart';

    public const SHIPPING_BASE = 50;      // ค่าส่งเริ่มต้น (บาท)

    public const SHIPPING_PER_KG = 15;    // บาท / กก. ถัดไป

    public const FREE_SHIPPING_MIN = 1000; // ส่งฟรีเมื่อซื้อครบ

    public function raw(): array
    {
        return session(self::KEY, []);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->raw();
        $product = Product::active()->findOrFail($productId);
        $newQty = min(($cart[$productId] ?? 0) + $qty, $product->stock);
        if ($newQty > 0) {
            $cart[$productId] = $newQty;
        }
        session([self::KEY => $cart]);
    }

    public function update(int $productId, int $qty): void
    {
        $cart = $this->raw();
        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $stock = Product::whereKey($productId)->value('stock') ?? 0;
            $cart[$productId] = min($qty, $stock);
        }
        session([self::KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    /** @return Collection<int, array{product: Product, qty: int, line_total: float}> */
    public function items(): Collection
    {
        $cart = $this->raw();
        if (! $cart) {
            return collect();
        }

        return Product::active()->with('community')->whereIn('id', array_keys($cart))->get()
            ->map(fn (Product $p) => [
                'product' => $p,
                'qty' => $cart[$p->id],
                'line_total' => (float) $p->price * $cart[$p->id],
            ])->values();
    }

    public function subtotal(): float
    {
        return $this->items()->sum('line_total');
    }

    public function shippingFee(): float
    {
        $subtotal = $this->subtotal();
        if ($subtotal == 0 || $subtotal >= self::FREE_SHIPPING_MIN) {
            return 0;
        }
        $grams = $this->items()->sum(fn ($i) => $i['product']->weight_gram * $i['qty']);
        $extraKg = max(0, (int) ceil($grams / 1000) - 1);

        return self::SHIPPING_BASE + $extraKg * self::SHIPPING_PER_KG;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shippingFee();
    }
}
