<div>
    <section class="bg-sun-50">
        <div class="container-page grid gap-6 py-12 sm:py-16 md:grid-cols-[1fr_auto] md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">Made by local hands</p>
                <h1 class="mt-3 font-display text-4xl font-semibold sm:text-5xl">ของดีจากชุมชน<br><span class="text-leaf-700">ส่งเรื่องราวถึงบ้านคุณ</span></h1>
                <p class="mt-4 max-w-2xl leading-7 text-muted">เลือกชมผลิตภัณฑ์ท้องถิ่นและของฝากจากผู้ผลิตในชุมชน ซื้อผ่านระบบได้ตามเดิม พร้อมช่วยสนับสนุนเศรษฐกิจในพื้นที่</p>
            </div>
            <a href="{{ route('communities.index') }}" wire:navigate class="font-semibold text-leaf-800 hover:underline">รู้จักชุมชนผู้ผลิต →</a>
        </div>
    </section>

    <div class="container-page grid gap-8 py-10 lg:grid-cols-[260px_minmax(0,1fr)]">
        {{-- ===== ตัวกรอง ===== --}}
        <aside class="h-fit space-y-6 rounded-3xl border border-sand bg-white p-5 shadow-soft lg:sticky lg:top-24">
            <div>
                <p class="mb-2 text-sm font-semibold">ค้นหาสินค้าชุมชน</p>
                <input wire:model.live.debounce.400ms="q" type="search" class="input" placeholder="ชื่อสินค้า หรือผู้ผลิต">
            </div>

            <div>
                <p class="mb-3 text-sm font-semibold">เลือกตามหมวดหมู่</p>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('category', '')"
                            @class(['rounded-full px-4 py-2 text-left text-sm transition', 'bg-leaf-700 text-white' => !$category, 'bg-[#f0eee4] text-ink hover:bg-leaf-50' => $category])>
                        ทั้งหมด
                    </button>
                    @foreach ($categories as $c)
                        <button wire:click="$set('category', '{{ $c->slug }}')"
                                @class(['flex items-center gap-2 rounded-full px-4 py-2 text-left text-sm transition',
                                        'bg-leaf-700 text-white' => $category === $c->slug,
                                        'bg-[#f0eee4] text-ink hover:bg-leaf-50' => $category !== $c->slug])>
                            <span>{{ $c->icon }} {{ $c->name }}</span>
                            <span class="text-xs opacity-70">({{ $c->products_count }})</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <label class="flex cursor-pointer items-center gap-3 border-t border-sand pt-5 text-sm">
                <input type="checkbox" wire:model.live="otop" class="h-4 w-4 rounded accent-sun-500">
                <span>แสดงเฉพาะสินค้า OTOP</span>
            </label>
        </aside>

        {{-- ===== รายการสินค้า ===== --}}
        <section class="min-w-0">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-muted">พบ <strong class="text-ink">{{ $products->total() }}</strong> ผลิตภัณฑ์</p>
                <select wire:model.live="sort" class="input w-auto">
                    <option value="latest">ใหม่ล่าสุด</option>
                    <option value="price_asc">ราคา ต่ำ → สูง</option>
                    <option value="price_desc">ราคา สูง → ต่ำ</option>
                </select>
            </div>
            <div wire:loading.class="opacity-50" class="grid grid-cols-2 gap-4 sm:gap-5 xl:grid-cols-3">
                @forelse ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-sand bg-white p-12 text-center">
                        <p class="font-display text-xl font-semibold">ยังไม่พบสินค้าที่ตรงกัน</p>
                        <p class="mt-2 text-sm text-muted">ลองเปลี่ยนคำค้นหรือเลือกหมวดหมู่อื่น</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        </section>
    </div>
</div>
