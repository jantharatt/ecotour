{{-- ตัวเลือกพิกัด: คลิกบนแผนที่หรือลากหมุด, ผูกกับ form.lat / form.lng ของคอมโพเนนต์ --}}
<div class="sm:col-span-2" wire:key="picker-{{ $editingId ?? 'new' }}">
    <div class="mb-1 flex items-center justify-between">
        <label class="label mb-0">ตำแหน่งบนแผนที่</label>
    </div>
    <div x-data="locationPicker({ lat: $wire.entangle('form.lat'), lng: $wire.entangle('form.lng') })" wire:ignore>
        <div x-ref="map" class="h-72 w-full overflow-hidden rounded-xl ring-1 ring-sand"></div>
        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-muted">
            <span>คลิกบนแผนที่เพื่อปักหมุด หรือลากหมุดเพื่อปรับ</span>
            <button type="button" x-on:click="useMyLocation()" class="btn-soft btn-sm ml-auto">📍 ใช้ตำแหน่งปัจจุบันของฉัน</button>
        </div>
    </div>
    <div class="mt-2 grid grid-cols-2 gap-2">
        <div>
            <input wire:model.live.debounce.600ms="form.lat" class="input font-mono" placeholder="ละติจูด เช่น 14.3395200">
            @error('form.lat') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <input wire:model.live.debounce.600ms="form.lng" class="input font-mono" placeholder="ลองจิจูด เช่น 100.5582300">
            @error('form.lng') <p class="error">{{ $message }}</p> @enderror
        </div>
    </div>
    <p class="mt-1 text-xs text-muted">💡 คัดลอกพิกัดจาก Google Maps ได้: คลิกขวาที่ตำแหน่ง → คลิกตัวเลขพิกัดเพื่อคัดลอก</p>
</div>
