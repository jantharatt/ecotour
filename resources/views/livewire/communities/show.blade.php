<div>
    <section class="relative isolate overflow-hidden bg-leaf-900 text-white">
        <img src="{{ $community->cover_url }}" alt="ภาพประกอบ{{ $community->name }}" class="absolute inset-0 -z-10 h-full w-full object-cover opacity-45">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink/95 via-leaf-900/80 to-leaf-900/30"></div>
        <div class="container-page grid min-h-[360px] items-end py-12 sm:min-h-[420px] sm:py-16">
            <div class="max-w-3xl">
                <a href="{{ route('communities.index') }}" wire:navigate class="text-sm font-medium text-white/70 hover:text-white">← ชุมชนทั้งหมด</a>
                <p class="mt-7 text-sm font-semibold uppercase tracking-[.18em] text-sun-200">{{ $community->location_text ?: 'ชุมชนในอยุธยา' }}</p>
                <h1 class="mt-3 font-display text-4xl font-semibold text-white sm:text-5xl">{{ $community->name }}</h1>
                @if ($community->tagline)
                    <p class="mt-3 text-xl text-white/80">{{ $community->tagline }}</p>
                @endif
            </div>
        </div>
    </section>

    <div class="container-page grid gap-10 py-12 sm:py-16 lg:grid-cols-[minmax(0,1fr)_340px]">
        <div class="min-w-0 space-y-12">
            <section>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">Our roots & everyday life</p>
                <h2 class="mt-2 font-display text-3xl font-semibold">ที่มาและเรื่องราวของชุมชน</h2>
                <div class="mt-5 whitespace-pre-line text-base leading-8 text-muted">{{ $community->description }}</div>
            </section>

            <section class="rounded-3xl border border-sand bg-white p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">A living community</p>
                <h2 class="mt-2 font-display text-2xl font-semibold">ชุมชนที่ยังมีชีวิต ไม่ใช่แค่จุดหมาย</h2>
                <p class="mt-4 leading-7 text-muted">การมาเยือนคือโอกาสได้เรียนรู้ว่าผู้คนใช้ชีวิต ดูแลบ้าน และสืบสานสิ่งสำคัญของพื้นที่อย่างไร หากต้องการพูดคุยหรือทำกิจกรรมกับชุมชน กรุณาติดต่อเจ้าบ้านก่อนเดินทาง</p>
                @if ($community->conservation)
                    <div class="mt-6 rounded-2xl bg-leaf-50 p-5">
                        <h3 class="font-display text-lg font-semibold text-leaf-800">สิ่งที่ชุมชนร่วมกันดูแล</h3>
                        <ul class="mt-3 grid gap-2 text-sm leading-6 text-leaf-800 sm:grid-cols-2">
                            @foreach (preg_split('/\r?\n/', $community->conservation) as $line)
                                @if (trim($line))
                                    <li class="flex gap-2"><span aria-hidden="true">✳</span><span>{{ $line }}</span></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>

            @if ($community->attractions->isNotEmpty())
                <section>
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">Explore the neighborhood</p>
                            <h2 class="mt-2 font-display text-2xl font-semibold">สถานที่และเรื่องราวใกล้บ้าน</h2>
                        </div>
                        <span class="text-sm text-muted">{{ $community->attractions->count() }} จุดแวะ</span>
                    </div>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        @foreach ($community->attractions as $place)
                            @include('partials.place-card', ['place' => $place->setRelation('community', $community)])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($community->products->isNotEmpty())
                <section class="rounded-3xl bg-[#f0eee4] p-6 sm:p-8">
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">Made in this community</p>
                            <h2 class="mt-2 font-display text-2xl font-semibold">เลือกสนับสนุนผลิตภัณฑ์ชุมชน</h2>
                        </div>
                        <a href="{{ route('shop.index') }}" wire:navigate class="text-sm font-semibold text-leaf-700 hover:underline">ดูร้านของฝากทั้งหมด →</a>
                    </div>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-muted">ทุกชิ้นมีผู้ผลิตและเรื่องราวอยู่เบื้องหลัง เลือกชมสินค้าและสั่งซื้อผ่านระบบได้ตามปกติ</p>
                    <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($community->products as $product)
                            @include('partials.product-card', ['product' => $product, 'canAdd' => false])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
            @if (count($markers))
                @include('partials.map', ['markers' => $markers, 'height' => 'h-72'])
            @endif
            <div class="rounded-3xl border border-sand bg-white p-6 shadow-soft">
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">Say hello</p>
                <h2 class="mt-2 font-display text-xl font-semibold">ติดต่อชุมชน</h2>
                <div class="mt-4 space-y-3 text-sm text-muted">
                    @if ($community->contact_name) <p>👤 {{ $community->contact_name }}</p> @endif
                    @if ($community->contact_phone) <p>☎️ <a href="tel:{{ $community->contact_phone }}" class="font-medium text-lagoon-700 underline">{{ $community->contact_phone }}</a></p> @endif
                    @if ($community->line_id) <p>💬 LINE: {{ $community->line_id }}</p> @endif
                    @if ($community->facebook_url) <p>📘 <a href="{{ $community->facebook_url }}" target="_blank" rel="noopener" class="font-medium text-lagoon-700 underline">Facebook</a></p> @endif
                    @if ($community->address) <p>🏠 {{ $community->address }}</p> @endif
                </div>
                @if ($community->directions_url)
                    <a href="{{ $community->directions_url }}" target="_blank" rel="noopener" class="btn-accent mt-5 w-full">นำทางไปชุมชน <span aria-hidden="true">↗</span></a>
                @endif
            </div>
            <div class="rounded-3xl bg-leaf-900 p-6 text-white">
                <p class="text-sm font-semibold uppercase tracking-[.16em] text-sun-200">Visit thoughtfully</p>
                <p class="mt-3 text-sm leading-6 text-white/75">เคารพความเชื่อ ขออนุญาตก่อนถ่ายภาพบุคคล และช่วยกันรักษาความสงบกับความสะอาดของชุมชน</p>
            </div>
        </aside>
    </div>
</div>
