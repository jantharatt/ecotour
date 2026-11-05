<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('ตะกร้าสินค้า')]
class Page extends Component
{
    public function updateQty(int $productId, int $qty, CartService $cart): void
    {
        $cart->update($productId, $qty);
        $this->dispatch('cart-updated');
    }

    public function remove(int $productId, CartService $cart): void
    {
        $cart->remove($productId);
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cart)
    {
        return view('livewire.cart.page', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'shipping' => $cart->shippingFee(),
            'total' => $cart->total(),
            'freeMin' => CartService::FREE_SHIPPING_MIN,
        ]);
    }
}
