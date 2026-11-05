<div class="container-page py-8">
    <nav class="text-sm text-muted">
        <a href="{{ route('places.index') }}" wire:navigate class="hover:text-leaf-700">แหล่งท่องเที่ยว</a> /
        <a href="{{ route('communities.show', $place->community) }}" wire:navigate class="hover:text-leaf-700">{{ $place->community->name }}</a> /
        {{ $place->name }}
    </nav>

    <div class="mt-4 grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="relative overflow-hidden rounded-[2rem] bg-leaf-50 shadow-soft">
                <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="aspect-[16/9] w-full object-cover">
                @unless ($place->image)
                    <span class="absolute bottom-4 left-4 rounded-full bg-ink/70 px-4 py-2 text-xs font-medium text-white">ภาพประกอบชั่วคราว · เพิ่มภาพจริงได้จากหลังบ้าน</span>
                @endunless
            </div>

            @if ($place->gallery)
                <div class="grid grid-cols-4 gap-2">
                    @foreach ($place->gallery as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="aspect-square rounded-xl object-cover" alt="" loading="lazy">
                    @endforeach
                </div>
            @endif

            <div>
                <div class="flex flex-wrap gap-2">
                    <span class="badge-lagoon">{{ $place->type_icon }} {{ $place->type_label }}</span>
                    @if ($place->faith)
                        @php([$fl, $fi, $fc] = \App\Models\Attraction::FAITHS[$place->faith])
                        <span class="badge text-white" style="background: {{ $fc }}">{{ $fi }} ศาสนา{{ $fl }}</span>
                    @endif
                    <span class="badge-gray">👁 {{ number_format($place->views) }} ครั้ง</span>
                </div>
                <h1 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">{{ $place->name }}</h1>
                <p class="mt-3 text-lg leading-8 text-muted">{{ $place->summary }}</p>
                <div class="mt-5 whitespace-pre-line leading-8 text-muted">{{ $place->description }}</div>
            </div>

            @if ($place->eco_tips)
                <div class="rounded-[var(--radius-card)] bg-leaf-50 p-6 ring-1 ring-leaf-200">
                    <h2 class="text-lg font-semibold text-leaf-800">🌿 ข้อควรปฏิบัติ</h2>
                    <ul class="mt-2 space-y-1 text-sm text-leaf-800">
                        @foreach (preg_split('/\r?\n/', $place->eco_tips) as $tip)
                            @if (trim($tip)) <li>✔︎ {{ $tip }}</li> @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($nearby->isNotEmpty())
                <div>
                    <h2 class="text-xl font-semibold">สถานที่ใกล้เคียง</h2>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        @foreach ($nearby as $n)
                            <a href="{{ route('places.show', $n) }}" wire:navigate class="card flex items-center gap-3 p-4 hover:ring-leaf-300">
                                <span class="text-2xl">{{ $n->faith ? \App\Models\Attraction::FAITHS[$n->faith][1] : $n->type_icon }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium">{{ $n->name }}</p>
                                    <p class="text-xs text-muted">{{ $n->community->name }}</p>
                                </div>
                                <span class="badge-lagoon shrink-0">{{ number_format($n->distance_km, 1) }} กม.</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== ข้อมูลการเดินทาง ===== --}}
        <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
            @if ($place->lat && $place->lng)
                @include('partials.map', ['markers' => $markers, 'height' => 'h-72'])
            @endif

            <div class="card space-y-3 p-6 text-sm">
                <h2 class="text-lg font-semibold">ข้อมูลการเดินทาง</h2>
                @foreach ([
                    ['📍', 'ที่ตั้ง', $place->address],
                    ['🕘', 'เวลาเปิด', $place->opening_hours],
                    ['🎟️', 'ค่าเข้าชม', $place->entry_fee],
                    ['🌤️', 'ช่วงเวลาแนะนำ', $place->best_time],
                    ['☎️', 'ติดต่อ', $place->contact_phone],
                ] as [$icon, $label, $value])
                    @if ($value)
                        <div class="flex gap-3"><span>{{ $icon }}</span><div><p class="text-xs text-muted">{{ $label }}</p><p>{{ $value }}</p></div></div>
                    @endif
                @endforeach
                @if ($place->lat && $place->lng)
                    <div class="flex gap-3"><span>🧭</span><div><p class="text-xs text-muted">พิกัด</p>
                        <p class="font-mono text-xs">{{ $place->lat }}, {{ $place->lng }}</p></div></div>
                @endif

                @if ($place->directions_url)
                    <a href="{{ $place->directions_url }}" target="_blank" rel="noopener" class="btn-accent w-full py-3">🧭 นำทางด้วย Google Maps</a>
                    <div x-data="{ copied: false }">
                        <button type="button" class="btn-outline w-full"
                                x-on:click="navigator.clipboard.writeText('{{ $place->lat }},{{ $place->lng }}'); copied = true; setTimeout(() => copied = false, 1500)">
                            <span x-text="copied ? '✓ คัดลอกพิกัดแล้ว' : '📋 คัดลอกพิกัด'"></span>
                        </button>
                    </div>
                @endif
                <p class="text-xs text-muted">* ควรตรวจสอบเวลาเปิด-ปิดล่าสุดก่อนเดินทาง</p>
            </div>

            @if ($products->isNotEmpty())
                <div class="card p-6">
                    <h2 class="font-semibold">🧺 ของฝากจาก{{ $place->community->name }}</h2>
                    <div class="mt-3 space-y-3">
                        @foreach ($products as $p)
                            <a href="{{ route('shop.show', $p) }}" wire:navigate class="flex items-center gap-3 rounded-xl p-1 hover:bg-cream">
                                <img src="{{ $p->image_url }}" class="h-12 w-12 rounded-lg object-cover" alt="">
                                <span class="flex-1 text-sm">{{ $p->name }}</span>
                                <span class="price text-sm">฿{{ number_format($p->price) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
