{{--
    แผนที่หมุดหลายจุด
    @include('partials.map', ['markers' => $markers, 'height' => 'h-96', 'locate' => true])
--}}
<div x-data="placesMap({ markers: @js($markers) })" wire:ignore class="relative">
    <div x-ref="map" class="{{ $height ?? 'h-96' }} w-full overflow-hidden rounded-[var(--radius-card)] ring-1 ring-black/5 shadow-soft"></div>

    @if ($locate ?? false)
        <div class="absolute right-3 top-3 z-[500] flex flex-col items-end gap-2">
            <button type="button" x-on:click="locate()" :disabled="locating"
                    class="btn rounded-full bg-white px-4 py-2 text-sm text-lagoon-700 shadow-soft hover:bg-lagoon-50">
                <span x-show="!locating">📍 ใกล้ฉัน</span>
                <span x-show="locating" style="display:none">กำลังหาตำแหน่ง...</span>
            </button>
            <p x-show="locateError" x-text="locateError" style="display:none"
               class="max-w-56 rounded-xl bg-white px-3 py-2 text-xs text-red-600 shadow-soft"></p>
        </div>
    @endif
</div>
