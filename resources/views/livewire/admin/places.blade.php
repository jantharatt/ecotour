<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.400ms="search" class="input max-w-xs" placeholder="🔍 ค้นหาสถานที่">
            <select wire:model.live="community" class="input w-auto">
                <option value="">ทุกชุมชน</option>
                @foreach ($communities as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <button wire:click="create" class="btn-primary">＋ เพิ่มสถานที่</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-admin">
            <thead><tr><th>สถานที่</th><th>ประเภท</th><th>ชุมชน</th><th>พิกัด</th><th class="text-right">เข้าชม</th><th>แนะนำ</th><th>สถานะ</th><th></th></tr></thead>
            <tbody>
            @forelse ($places as $p)
                <tr wire:key="pl-{{ $p->id }}">
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->image_url }}" class="h-10 w-14 rounded-lg object-cover" alt="">
                            <div>
                                <p class="font-medium">{{ $p->name }}</p>
                                @if ($p->faith)<p class="text-xs text-muted">{{ $faiths[$p->faith][1] }} ศาสนา{{ $faiths[$p->faith][0] }}</p>@endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $p->type_icon }} {{ $p->type_label }}</td>
                    <td class="text-muted">{{ $p->community->name }}</td>
                    <td>
                        @if ($p->lat && $p->lng)
                            <a href="{{ $p->directions_url }}" target="_blank" class="badge-leaf">✓ มีพิกัด</a>
                        @else
                            <span class="badge-red">ยังไม่ระบุ</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($p->views) }}</td>
                    <td><button wire:click="toggle({{ $p->id }}, 'is_featured')" class="text-lg">{{ $p->is_featured ? '⭐' : '☆' }}</button></td>
                    <td><button wire:click="toggle({{ $p->id }}, 'is_active')" class="{{ $p->is_active ? 'badge-leaf' : 'badge-gray' }}">{{ $p->is_active ? 'แสดง' : 'ซ่อน' }}</button></td>
                    <td class="whitespace-nowrap text-right">
                        <a href="{{ route('places.show', $p) }}" target="_blank" class="btn-sm text-muted hover:text-ink">ดู</a>
                        <button wire:click="edit({{ $p->id }})" class="btn-soft btn-sm">แก้ไข</button>
                        <button wire:click="delete({{ $p->id }})" wire:confirm="ลบ {{ $p->name }}?" class="btn-sm text-red-600 hover:underline">ลบ</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="py-10 text-center text-muted">ยังไม่มีสถานที่</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $places->links() }}

    <x-modal model="showForm" :title="$editingId ? 'แก้ไขสถานที่' : 'เพิ่มสถานที่ใหม่'" max-width="max-w-3xl">
        @if ($showForm)
        <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label">ชื่อสถานที่ *</label>
                <input wire:model="form.name" class="input">
                @error('form.name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">ชุมชน *</label>
                <select wire:model="form.community_id" class="input">
                    @foreach ($communities as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="label">ประเภท *</label>
                    <select wire:model="form.type" class="input">
                        @foreach ($types as $k => [$label, $icon])<option value="{{ $k }}">{{ $icon }} {{ $label }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label">ศาสนา (ถ้ามี)</label>
                    <select wire:model="form.faith" class="input">
                        <option value="">—</option>
                        @foreach ($faiths as $k => [$label, $icon])<option value="{{ $k }}">{{ $icon }} {{ $label }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="sm:col-span-2"><label class="label">คำโปรยสั้น</label><input wire:model="form.summary" class="input" maxlength="300"></div>
            <div class="sm:col-span-2"><label class="label">รายละเอียด / ประวัติ</label><textarea wire:model="form.description" rows="5" class="input"></textarea></div>

            @include('partials.location-picker')

            <div class="sm:col-span-2"><label class="label">ที่อยู่</label><input wire:model="form.address" class="input"></div>
            <div><label class="label">เวลาเปิด–ปิด</label><input wire:model="form.opening_hours" class="input" placeholder="ทุกวัน 08:00–17:00"></div>
            <div><label class="label">ค่าเข้าชม</label><input wire:model="form.entry_fee" class="input" placeholder="ฟรี / 20 บาท"></div>
            <div><label class="label">ช่วงเวลาแนะนำ</label><input wire:model="form.best_time" class="input" placeholder="ช่วงเย็น / ฤดูหนาว"></div>
            <div><label class="label">เบอร์ติดต่อ</label><input wire:model="form.contact_phone" class="input"></div>
            <div class="sm:col-span-2"><label class="label">ข้อควรปฏิบัติ (บรรทัดละ 1 ข้อ)</label><textarea wire:model="form.eco_tips" rows="3" class="input"></textarea></div>

            <div>
                <label class="label">ภาพหลัก</label>
                <input type="file" wire:model="image" accept="image/*" class="input">
                @if ($image) <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-24 rounded-lg" alt=""> @endif
                @error('image') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">แกลเลอรี (เลือกได้หลายรูป)</label>
                <input type="file" wire:model="galleryUploads" accept="image/*" multiple class="input">
                @error('galleryUploads.*') <p class="error">{{ $message }}</p> @enderror
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($gallery as $i => $g)
                        <div class="relative" wire:key="g-{{ $i }}">
                            <img src="{{ asset('storage/'.$g) }}" class="h-14 w-14 rounded-lg object-cover" alt="">
                            <button type="button" wire:click="removeGalleryImage({{ $i }})" class="absolute -right-1 -top-1 grid h-5 w-5 place-items-center rounded-full bg-red-500 text-[10px] text-white">✕</button>
                        </div>
                    @endforeach
                    @foreach ($galleryUploads as $u)
                        <img src="{{ $u->temporaryUrl() }}" class="h-14 w-14 rounded-lg object-cover opacity-70" alt="">
                    @endforeach
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="form.is_featured" class="accent-sun-500"> ⭐ สถานที่แนะนำ (แสดงหน้าแรก)</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="form.is_active" class="accent-leaf-500"> แสดงบนเว็บไซต์</label>
            <div class="flex justify-end gap-2 sm:col-span-2">
                <button type="button" wire:click="$set('showForm', false)" class="btn-outline">ยกเลิก</button>
                <button class="btn-primary" wire:loading.attr="disabled">บันทึก</button>
            </div>
        </form>
        @endif
    </x-modal>
</div>
