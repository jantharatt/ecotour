<a href="{{ route('cart') }}" wire:navigate aria-label="{{ $count > 0 ? 'ตะกร้าสินค้า '.$count.' รายการ' : 'ตะกร้าสินค้า' }}"
   class="relative inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-sun-50 px-4 text-base font-semibold text-sun-800 transition hover:bg-sun-100">
    <span aria-hidden="true">🧺</span>
    <span>ตะกร้า</span>
    @if ($count > 0)
        <span aria-hidden="true" class="absolute -right-1 -top-1 grid h-6 min-w-6 place-items-center rounded-full bg-sun-600 px-1 text-xs font-bold text-white">{{ $count }}</span>
    @endif
</a>
