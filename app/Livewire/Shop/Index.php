<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\CartService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('ร้านของฝากชุมชน')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $sort = 'latest';

    #[Url]
    public bool $otop = false;

    public function updated(): void
    {
        $this->resetPage();
    }

    public function addToCart(int $productId, CartService $cart): void
    {
        $cart->add($productId);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'เพิ่มลงตะกร้าแล้ว');
    }

    public function render()
    {
        $products = Product::active()->with(['category', 'community'])
            ->when($this->q, fn ($q) => $q->where('name', 'like', "%{$this->q}%"))
            ->when($this->category, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $this->category)))
            ->when($this->otop, fn ($q) => $q->where('is_otop', true))
            ->when($this->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($this->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($this->sort === 'latest', fn ($q) => $q->latest())
            ->paginate(12);

        return view('livewire.shop.index', [
            'products' => $products,
            'categories' => ProductCategory::withCount(['products' => fn ($q) => $q->active()])->get(),
        ]);
    }
}
