<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('ชำระเงิน')]
class Checkout extends Component
{
    #[Validate('required|string|max:100')]
    public string $ship_name = '';

    #[Validate('required|string|max:20')]
    public string $ship_phone = '';

    #[Validate('required|string|max:500')]
    public string $ship_address = '';

    #[Validate('required|in:promptpay,transfer,cod')]
    public string $payment_method = 'promptpay';

    public function mount(CartService $cart)
    {
        if ($cart->count() === 0) {
            return $this->redirectRoute('shop.index', navigate: true);
        }
        $user = Auth::user();
        $this->ship_name = $user->name;
        $this->ship_phone = (string) $user->phone;
        $this->ship_address = (string) $user->address;
    }

    public function placeOrder(CartService $cart)
    {
        $this->validate();

        $order = DB::transaction(function () use ($cart) {
            $items = $cart->items();

            // ตรวจและตัดสต็อกแบบล็อกแถว
            foreach ($items as $i) {
                $p = Product::whereKey($i['product']->id)->lockForUpdate()->first();
                if (! $p || $p->stock < $i['qty']) {
                    // throw เพื่อ rollback การตัดสต็อกที่ทำไปแล้วทั้งหมด
                    throw ValidationException::withMessages([
                        'stock' => "สินค้า {$i['product']->name} มีไม่พอ (เหลือ ".($p?->stock ?? 0).')',
                    ]);
                }
                $p->decrement('stock', $i['qty']);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $cart->subtotal(),
                'shipping_fee' => $cart->shippingFee(),
                'total' => $cart->total(),
                'payment_method' => $this->payment_method,
                'ship_name' => $this->ship_name,
                'ship_phone' => $this->ship_phone,
                'ship_address' => $this->ship_address,
            ]);

            foreach ($items as $i) {
                $order->items()->create([
                    'product_id' => $i['product']->id,
                    'product_name' => $i['product']->name,
                    'price' => $i['product']->price,
                    'qty' => $i['qty'],
                    'line_total' => $i['line_total'],
                ]);
            }

            // จำที่อยู่ไว้ใช้ครั้งถัดไป
            Auth::user()->update(['phone' => $this->ship_phone, 'address' => $this->ship_address]);

            return $order;
        });

        if ($order) {
            $cart->clear();
            $this->dispatch('cart-updated');
            session()->flash('success', 'สั่งซื้อสำเร็จ ขอบคุณที่อุดหนุนสินค้าชุมชน');

            return $this->redirectRoute('account.order', $order, navigate: true);
        }
    }

    public function render(CartService $cart)
    {
        return view('livewire.checkout', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'shipping' => $cart->shippingFee(),
            'total' => $cart->total(),
        ]);
    }
}
