<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class Counter extends Component
{
    #[On('cart-updated')]
    public function refresh(): void
    {
        // re-render เมื่อมีการเปลี่ยนแปลงตะกร้า
    }

    public function render(CartService $cart)
    {
        return view('livewire.cart.counter', ['count' => $cart->count()]);
    }
}
