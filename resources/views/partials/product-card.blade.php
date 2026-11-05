<article class="group flex flex-col overflow-hidden rounded-3xl border border-sand bg-white transition duration-300 hover:-translate-y-1 hover:shadow-soft">
    <a href="{{ route('shop.show', $product) }}" wire:navigate class="relative block aspect-square overflow-hidden bg-sun-50">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @unless ($product->image)
            <span class="absolute bottom-3 right-3 rounded-full bg-ink/60 px-3 py-1 text-[11px] text-white">ภาพประกอบ</span>
        @endunless
        @if ($product->is_otop)
            <span class="badge absolute left-3 top-3 bg-sun-600 text-white">OTOP</span>
        @endif
        @if ($product->stock < 1)
            <span class="absolute inset-0 grid place-items-center bg-white/70 font-semibold text-muted">สินค้าหมด</span>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-4 sm:p-5">
        <a href="{{ route('communities.show', $product->community) }}" wire:navigate class="inline-flex w-fit items-center gap-1 rounded-full bg-leaf-50 px-3 py-1 text-xs font-semibold text-leaf-800 hover:bg-leaf-100">
            <span aria-hidden="true">⌂</span> จากชุมชน {{ $product->community->name }}
        </a>
        <p class="mt-2 text-xs text-muted">{{ $product->producer }}</p>
        <a href="{{ route('shop.show', $product) }}" wire:navigate class="mt-1 line-clamp-2 min-h-10 text-sm font-semibold leading-5 hover:text-leaf-700">{{ $product->name }}</a>
        <div class="mt-auto flex items-center justify-between gap-2 border-t border-sand pt-4">
            <span class="font-display text-lg font-semibold text-sun-700">฿{{ number_format($product->price) }}</span>
            @if (($canAdd ?? true) && $product->stock > 0)
                <button wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled"
                        class="grid h-10 w-10 place-items-center rounded-full bg-leaf-900 text-lg text-white transition hover:bg-sun-600"
                        title="เพิ่มลงตะกร้า">＋</button>
            @endif
        </div>
    </div>
</article>
