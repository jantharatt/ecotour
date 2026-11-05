<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach ([
            ['ยอดขายเดือนนี้', '฿'.number_format($stats['sales_month']), 'text-sun-600', '💰'],
            ['ออเดอร์รอตรวจสลิป', $stats['orders_waiting'], 'text-lagoon-600', '🧾'],
            ['ออเดอร์รอจัดส่ง', $stats['orders_to_ship'], 'text-lagoon-600', '📦'],
            ['แหล่งท่องเที่ยว', $stats['places'], 'text-leaf-600', '📍'],
            ['ชุมชน', $stats['communities'], 'text-leaf-700', '🏡'],
        ] as [$label, $value, $color, $icon])
            <div class="card p-5">
                <p class="flex items-center gap-2 text-xs text-muted"><span>{{ $icon }}</span>{{ $label }}</p>
                <p class="mt-2 font-display text-2xl font-semibold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    @if ($missingLocation)
        <a href="{{ route('admin.places') }}" wire:navigate class="block rounded-2xl bg-sun-50 px-5 py-3 text-sm text-sun-700 ring-1 ring-sun-200">
            ⚠️ มี {{ $missingLocation }} สถานที่ที่ยังไม่ระบุพิกัด จะไม่แสดงบนแผนที่ → ไปแก้ไข
        </a>
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="card p-6 xl:col-span-2">
            <h2 class="font-semibold">ยอดขายสินค้า 6 เดือนล่าสุด</h2>
            @php($max = max(1, $months->max('total')))
            <div class="mt-6 flex h-56 items-end gap-4">
                @foreach ($months as $m)
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <span class="text-[11px] text-muted">{{ $m['total'] ? '฿'.number_format($m['total']) : '' }}</span>
                        <div class="flex h-44 w-full items-end justify-center">
                            <div class="w-2/3 rounded-t-lg bg-sun-400" style="height: {{ $m['total'] / $max * 100 }}%"></div>
                        </div>
                        <span class="text-xs text-muted">{{ $m['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card p-6">
            <h2 class="font-semibold">⚠️ สินค้าใกล้หมด</h2>
            <ul class="mt-3 divide-y divide-sand text-sm">
                @forelse ($lowStock as $p)
                    <li class="flex justify-between py-2"><span>{{ $p->name }}</span><span class="{{ $p->stock < 5 ? 'text-red-600' : 'text-sun-600' }} font-semibold">{{ $p->stock }}</span></li>
                @empty
                    <li class="py-2 text-muted">สต็อกเพียงพอทุกรายการ</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="card p-6">
            <h2 class="font-semibold">📍 สถานที่ยอดนิยม (ยอดเข้าชม)</h2>
            <ul class="mt-3 divide-y divide-sand text-sm">
                @forelse ($topPlaces as $i => $p)
                    <li class="flex justify-between py-2"><span>{{ $i + 1 }}. {{ $p->name }}</span><span class="text-muted">{{ number_format($p->views) }} ครั้ง</span></li>
                @empty
                    <li class="py-2 text-muted">ยังไม่มีข้อมูล</li>
                @endforelse
            </ul>
        </div>
        <div class="card p-6">
            <h2 class="font-semibold">🧺 สินค้าขายดี</h2>
            <ul class="mt-3 divide-y divide-sand text-sm">
                @forelse ($topProducts as $i => $p)
                    <li class="flex justify-between py-2"><span>{{ $i + 1 }}. {{ $p->product_name }}</span><span class="text-muted">{{ $p->qty }} ชิ้น · ฿{{ number_format($p->revenue) }}</span></li>
                @empty
                    <li class="py-2 text-muted">ยังไม่มียอดขาย</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
