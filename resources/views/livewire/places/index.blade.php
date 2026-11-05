<div>
    <section class="bg-leaf-900 text-white">
        <div class="container-page py-10 sm:py-14">
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-200">Plan a thoughtful journey</p>
            <h1 class="mt-3 font-display text-3xl font-semibold text-white sm:text-4xl">ออกเดินทางไปพบอยุธยา</h1>
            <p class="mt-2 max-w-2xl leading-7 text-white/70">ค้นหาศาสนสถาน ชุมชน และจุดสำคัญริมสายน้ำ เลือกชุมชนที่อยากรู้จักแล้วค่อย ๆ วางแผนเส้นทาง</p>

            {{-- ตัวกรองศาสนา --}}
            <div class="mt-6 flex flex-wrap gap-2">
                <button wire:click="$set('faith', '')" @class(['rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink text-white' => !$faith, 'bg-white hover:bg-leaf-50' => $faith])>ทั้งหมด</button>
                @foreach ($faiths as $key => [$label, $icon, $color])
                    <button wire:click="$set('faith', '{{ $key }}')"
                            class="rounded-full px-4 py-2 text-sm font-medium transition {{ $faith === $key ? 'text-white' : 'bg-white hover:opacity-80' }}"
                            @if ($faith === $key) style="background: {{ $color }}" @endif>
                        {{ $icon }} ศาสนา{{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-3 grid gap-3 rounded-2xl bg-white p-3 shadow-soft sm:grid-cols-4">
                <input wire:model.live.debounce.400ms="q" type="search" class="input sm:col-span-2" placeholder="🔍 ค้นหาชื่อสถานที่">
                <select wire:model.live="type" class="input">
                    <option value="">ทุกประเภท</option>
                    @foreach ($types as $k => [$label, $icon])
                        <option value="{{ $k }}">{{ $icon }} {{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="community" class="input">
                    <option value="">ทุกชุมชน</option>
                    @foreach ($communities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <div class="container-page mt-8 grid gap-8 lg:grid-cols-5">
        {{-- แผนที่ (ติดอยู่ด้านข้างบนจอใหญ่) --}}
        <div class="lg:sticky lg:top-24 lg:col-span-3 lg:self-start">
            @include('partials.map', ['markers' => $markers, 'height' => 'h-[360px] lg:h-[calc(100vh-8rem)]', 'locate' => true])
        </div>

        {{-- รายการ --}}
        <div class="lg:col-span-2">
            <div class="mb-3 flex items-center justify-between text-sm">
                <p class="text-muted">
                    พบ {{ $places->count() }} แห่ง
                    @if ($userLat) · <span class="font-medium text-lagoon-700">เรียงจากใกล้คุณที่สุด</span> @endif
                </p>
                <div class="flex gap-3">
                    @if ($userLat)<button wire:click="clearLocation" class="text-muted hover:text-ink">ยกเลิกใกล้ฉัน</button>@endif
                    @if ($q || $type || $faith || $community)<button wire:click="resetFilters" class="text-leaf-700 hover:underline">ล้างตัวกรอง</button>@endif
                </div>
            </div>
            <div wire:loading.class="opacity-50" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                @forelse ($places as $place)
                    <div wire:key="place-{{ $place->id }}">@include('partials.place-card', ['place' => $place])</div>
                @empty
                    <div class="rounded-2xl bg-white p-12 text-center text-muted sm:col-span-2 lg:col-span-1">ไม่พบสถานที่ตามเงื่อนไข</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
