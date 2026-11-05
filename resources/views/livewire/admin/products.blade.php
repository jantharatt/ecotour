<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <input wire:model.live.debounce.400ms="search" class="input max-w-xs" placeholder="🔍 ค้นหาสินค้า">
        <button wire:click="create" class="btn-primary">＋ เพิ่มสินค้า</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-admin">
            <thead><tr><th>สินค้า</th><th>หมวด</th><th>ชุมชน</th><th class="text-right">ราคา</th><th class="text-right">สต็อก</th><th>สถานะ</th><th></th></tr></thead>
            <tbody>
            @foreach ($products as $p)
                <tr wire:key="p-{{ $p->id }}">
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->image_url }}" class="h-10 w-10 rounded-lg object-cover" alt="">
                            <div><p class="font-medium">{{ $p->name }}</p>@if ($p->is_otop)<span class="badge-sun">OTOP</span>@endif</div>
                        </div>
                    </td>
                    <td>{{ $p->category->name }}</td>
                    <td class="text-muted">{{ $p->community->name }}</td>
                    <td class="text-right">฿{{ number_format($p->price) }}</td>
                    <td class="text-right {{ $p->stock < 10 ? 'text-red-600 font-semibold' : '' }}">{{ $p->stock }}</td>
                    <td>
                        <button wire:click="toggleActive({{ $p->id }})" class="{{ $p->is_active ? 'badge-leaf' : 'badge-gray' }}">
                            {{ $p->is_active ? 'แสดง' : 'ซ่อน' }}
                        </button>
                    </td>
                    <td class="whitespace-nowrap text-right">
                        <button wire:click="edit({{ $p->id }})" class="btn-soft btn-sm">แก้ไข</button>
                        <button wire:click="delete({{ $p->id }})" wire:confirm="ลบสินค้า {{ $p->name }}?" class="btn-sm text-red-600 hover:underline">ลบ</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}

    <x-modal model="showForm" :title="$editingId ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่'">
        <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label">ชื่อสินค้า *</label>
                <input wire:model="form.name" class="input">
                @error('form.name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">ชุมชน *</label>
                <select wire:model="form.community_id" class="input">
                    @foreach ($communities as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="label">หมวดหมู่ *</label>
                <select wire:model="form.product_category_id" class="input">
                    @foreach ($categories as $c)<option value="{{ $c->id }}">{{ $c->icon }} {{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="label">ราคา (บาท) *</label>
                <input type="number" step="0.01" wire:model="form.price" class="input">
                @error('form.price') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div><label class="label">สต็อก *</label><input type="number" wire:model="form.stock" class="input"></div>
                <div><label class="label">หน่วย</label><input wire:model="form.unit" class="input"></div>
            </div>
            <div><label class="label">ผู้ผลิต/กลุ่ม</label><input wire:model="form.producer" class="input"></div>
            <div><label class="label">น้ำหนัก (กรัม) สำหรับค่าส่ง</label><input type="number" wire:model="form.weight_gram" class="input"></div>
            <div class="sm:col-span-2"><label class="label">เรื่องเล่าผลิตภัณฑ์</label><textarea wire:model="form.story" rows="2" class="input"></textarea></div>
            <div class="sm:col-span-2"><label class="label">รายละเอียด</label><textarea wire:model="form.description" rows="3" class="input"></textarea></div>
            <div class="sm:col-span-2">
                <label class="label">รูปสินค้า</label>
                <input type="file" wire:model="image" accept="image/*" class="input">
                @if ($image) <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-24 rounded-lg" alt=""> @endif
                @error('image') <p class="error">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="form.is_otop" class="accent-sun-500"> สินค้า OTOP</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="form.is_active" class="accent-leaf-500"> แสดงบนหน้าร้าน</label>
            <div class="flex justify-end gap-2 sm:col-span-2">
                <button type="button" wire:click="$set('showForm', false)" class="btn-outline">ยกเลิก</button>
                <button class="btn-primary" wire:loading.attr="disabled">บันทึก</button>
            </div>
        </form>
    </x-modal>
</div>
