<div class="container-page py-8">
    <nav class="text-sm text-muted">
        <a href="{{ route('shop.index') }}" wire:navigate class="hover:text-leaf-700">ร้านของฝาก</a> /
        {{ $product->category->name }} / {{ $product->name }}
    </nav>

    <div class="mt-4 grid gap-10 md:grid-cols-2">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-[2rem] bg-sun-50 object-cover shadow-soft">

        <div>
            <div class="flex gap-2">
                <span class="badge-leaf">{{ $product->category->icon }} {{ $product->category->name }}</span>
                @if ($product->is_otop) <span class="badge-sun">OTOP</span> @endif
            </div>
            <h1 class="mt-3 text-3xl font-bold">{{ $product->name }}</h1>
            <p class="mt-1 text-sm text-muted">
                ผลิตโดย {{ $product->producer }} ·
                <a href="{{ route('communities.show', $product->community) }}" wire:navigate class="text-lagoon-600 hover:underline">{{ $product->community->name }}</a>
            </p>

            <p class="price mt-5 text-4xl">฿{{ number_format($product->price) }}
                <span class="text-base font-normal text-muted">/ {{ $product->unit }}</span></p>

            <p @class(['mt-2 text-sm', 'text-leaf-600' => $product->stock > 10, 'text-sun-600' => $product->stock <= 10])>
                {{ $product->stock > 0 ? "มีสินค้า {$product->stock} {$product->unit}" : 'สินค้าหมด' }}
            </p>

            @if ($product->stock > 0)
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <div class="flex items-center rounded-full bg-white ring-1 ring-sand">
                        <button wire:click="decrement" class="h-11 w-11 rounded-full text-lg hover:bg-leaf-50">−</button>
                        <span class="w-10 text-center font-semibold">{{ $qty }}</span>
                        <button wire:click="increment" class="h-11 w-11 rounded-full text-lg hover:bg-leaf-50">＋</button>
                    </div>
                    <button wire:click="addToCart" class="btn-outline py-3">🧺 ใส่ตะกร้า</button>
                    <button wire:click="buyNow" class="btn-accent py-3">ซื้อเลย</button>
                </div>
            @endif

            @if ($product->story)
                <div class="mt-8 rounded-2xl bg-sun-50 p-5 ring-1 ring-sun-100">
                    <p class="font-display font-semibold text-sun-700">📖 เรื่องเล่าจากผู้ผลิต</p>
                    <p class="mt-2 text-sm leading-relaxed">{{ $product->story }}</p>
                </div>
            @endif

            @if ($product->description)
                <div class="mt-6">
                    <p class="font-semibold">รายละเอียดสินค้า</p>
                    <div class="mt-2 whitespace-pre-line text-sm leading-relaxed text-muted">{{ $product->description }}</div>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-3 gap-3 text-center text-xs text-muted">
                <div class="rounded-xl bg-white p-3">🚚<br>ส่งฟรีเมื่อซื้อครบ ฿1,000</div>
                <div class="rounded-xl bg-white p-3">🤲<br>ทำมือในชุมชน</div>
                <div class="rounded-xl bg-white p-3">♻️<br>บรรจุภัณฑ์รักษ์โลก</div>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="section-title">สินค้าใกล้เคียง</h2>
            <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($related as $p)
                    @include('partials.product-card', ['product' => $p, 'canAdd' => false])
                @endforeach
            </div>
        </section>
    @endif
</div>
