<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class Show extends Component
{
    public Product $product;

    public int $qty = 1;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);
        $this->product = $product->load('community', 'category');
    }

    public function increment(): void
    {
        $this->qty = min($this->qty + 1, $this->product->stock);
    }

    public function decrement(): void
    {
        $this->qty = max(1, $this->qty - 1);
    }

    public function addToCart(CartService $cart): void
    {
        $cart->add($this->product->id, $this->qty);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'เพิ่มลงตะกร้าแล้ว');
    }

    public function buyNow(CartService $cart)
    {
        $cart->add($this->product->id, $this->qty);

        return $this->redirectRoute('checkout', navigate: true);
    }

    public function render()
    {
        return view('livewire.shop.show', [
            'related' => Product::active()
                ->with('community')
                ->where('product_category_id', $this->product->product_category_id)
                ->whereKeyNot($this->product->id)->take(4)->get(),
        ])->title($this->product->name);
    }
}
