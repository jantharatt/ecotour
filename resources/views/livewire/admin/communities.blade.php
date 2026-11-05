<div class="space-y-4">
    <div class="flex justify-end"><button wire:click="create" class="btn-primary">＋ เพิ่มชุมชน</button></div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($communities as $c)
            <div class="card overflow-hidden" wire:key="c-{{ $c->id }}">
                <img src="{{ $c->cover_url }}" class="aspect-[16/7] w-full object-cover" alt="">
                <div class="space-y-2 p-5">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-display text-lg font-semibold">{{ $c->name }}</p>
                        <span class="{{ $c->is_active ? 'badge-leaf' : 'badge-gray' }} shrink-0">{{ $c->is_active ? 'แสดง' : 'ซ่อน' }}</span>
                    </div>
                    <p class="text-sm text-muted">{{ $c->location_text }}</p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <a href="{{ route('admin.places', ['community' => $c->id]) }}" wire:navigate class="badge-lagoon">📍 {{ $c->attractions_count }} สถานที่</a>
                        <span class="badge-sun">🧺 {{ $c->products_count }} สินค้า</span>
                        @unless ($c->lat)<span class="badge-red">ยังไม่ระบุพิกัด</span>@endunless
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button wire:click="edit({{ $c->id }})" class="btn-soft btn-sm flex-1">แก้ไข</button>
                        <a href="{{ route('communities.show', $c) }}" target="_blank" class="btn-outline btn-sm">ดูหน้าเว็บ</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-modal model="showForm" :title="$editingId ? 'แก้ไขชุมชน' : 'เพิ่มชุมชน'" max-width="max-w-3xl">
        @if ($showForm)
        <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2"><label class="label">ชื่อชุมชน *</label><input wire:model="form.name" class="input">
                @error('form.name') <p class="error">{{ $message }}</p> @enderror</div>
            <div class="sm:col-span-2"><label class="label">คำโปรย / จุดเด่น</label><input wire:model="form.tagline" class="input"></div>
            <div class="sm:col-span-2"><label class="label">เรื่องราวชุมชน</label><textarea wire:model="form.description" rows="5" class="input"></textarea></div>
            <div class="sm:col-span-2"><label class="label">งานอนุรักษ์ของชุมชน (บรรทัดละ 1 ข้อ)</label><textarea wire:model="form.conservation" rows="3" class="input"></textarea></div>
            <div><label class="label">ตำบล</label><input wire:model="form.subdistrict" class="input"></div>
            <div><label class="label">อำเภอ</label><input wire:model="form.district" class="input"></div>
            <div><label class="label">จังหวัด</label><input wire:model="form.province" class="input"></div>
            <div><label class="label">ที่อยู่ศูนย์ชุมชน</label><input wire:model="form.address" class="input"></div>

            @include('partials.location-picker')

            <div><label class="label">ผู้ประสานงาน</label><input wire:model="form.contact_name" class="input"></div>
            <div><label class="label">เบอร์ติดต่อ</label><input wire:model="form.contact_phone" class="input"></div>
            <div><label class="label">Facebook (URL)</label><input wire:model="form.facebook_url" class="input">
                @error('form.facebook_url') <p class="error">{{ $message }}</p> @enderror</div>
            <div><label class="label">LINE ID</label><input wire:model="form.line_id" class="input"></div>
            <div class="sm:col-span-2"><label class="label">ภาพปก</label><input type="file" wire:model="cover" accept="image/*" class="input">
                @error('cover') <p class="error">{{ $message }}</p> @enderror</div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="form.is_active" class="accent-leaf-500"> แสดงบนเว็บไซต์</label>
            <div class="flex justify-end gap-2 sm:col-span-2">
                <button type="button" wire:click="$set('showForm', false)" class="btn-outline">ยกเลิก</button>
                <button class="btn-primary">บันทึก</button>
            </div>
        </form>
        @endif
    </x-modal>
</div>
