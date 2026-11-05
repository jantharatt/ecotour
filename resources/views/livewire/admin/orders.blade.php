<div class="space-y-4">
    <div class="flex flex-wrap gap-3">
        <input wire:model.live.debounce.400ms="search" class="input max-w-xs" placeholder="🔍 รหัส / ชื่อผู้รับ">
        <select wire:model.live="status" class="input w-auto">
            <option value="">ทุกสถานะ</option>
            @foreach (\App\Models\Order::STATUSES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
        </select>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-admin">
            <thead><tr><th>รหัส</th><th>วันที่</th><th>ลูกค้า</th><th class="text-center">รายการ</th><th class="text-right">ยอด</th><th>ชำระ</th><th>สถานะ</th><th></th></tr></thead>
            <tbody>
            @forelse ($orders as $o)
                <tr wire:key="o-{{ $o->id }}">
                    <td class="font-mono text-xs">{{ $o->code }}</td>
                    <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $o->ship_name }}</td>
                    <td class="text-center">{{ $o->items_count }}</td>
                    <td class="text-right">฿{{ number_format($o->total) }}</td>
                    <td class="text-xs">{{ \App\Models\Order::PAYMENT_METHODS[$o->payment_method] }}</td>
                    <td>@include('partials.status-badge', ['status' => $o->status, 'label' => $o->status_label])</td>
                    <td class="text-right"><button wire:click="view({{ $o->id }})" class="btn-soft btn-sm">จัดการ</button></td>
                </tr>
            @empty
                <tr><td colspan="8" class="py-10 text-center text-muted">ไม่มีข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}

    @if ($viewing)
        <div class="fixed inset-0 z-50 grid place-items-center overflow-y-auto bg-ink/40 p-4" wire:click.self="$set('viewingId', null)">
            <div class="card w-full max-w-2xl space-y-4 p-6 text-sm">
                <div class="flex justify-between"><h3 class="text-lg font-semibold">คำสั่งซื้อ {{ $viewing->code }}</h3>
                    <button wire:click="$set('viewingId', null)" class="text-muted">✕</button></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        @foreach ($viewing->items as $i)
                            <p class="flex justify-between"><span>{{ $i->product_name }} × {{ $i->qty }}</span><span>฿{{ number_format($i->line_total) }}</span></p>
                        @endforeach
                        <p class="mt-2 flex justify-between border-t border-sand pt-2 font-semibold"><span>รวม (รวมค่าส่ง)</span><span>฿{{ number_format($viewing->total) }}</span></p>
                    </div>
                    <div class="rounded-xl bg-cream p-3">
                        <p class="font-medium">{{ $viewing->ship_name }} · {{ $viewing->ship_phone }}</p>
                        <p class="text-muted">{{ $viewing->ship_address }}</p>
                    </div>
                </div>
                @if ($viewing->payment_slip)
                    <a href="{{ asset('storage/'.$viewing->payment_slip) }}" target="_blank"><img src="{{ asset('storage/'.$viewing->payment_slip) }}" class="h-56 rounded-xl" alt="slip"></a>
                @endif
                <form wire:submit="saveTracking" class="flex gap-2">
                    <input wire:model="tracking_no" class="input" placeholder="เลขพัสดุ (บันทึกแล้วเปลี่ยนเป็น 'จัดส่งแล้ว')">
                    <button class="btn-primary shrink-0">บันทึก</button>
                </form>
                @error('tracking_no') <p class="error">{{ $message }}</p> @enderror
                <div class="flex flex-wrap gap-2 border-t border-sand pt-3">
                    @foreach (\App\Models\Order::STATUSES as $k => $v)
                        <button wire:click="setStatus({{ $viewing->id }}, '{{ $k }}')"
                                @if ($k === 'cancelled') wire:confirm="ยกเลิกออเดอร์และคืนสต็อก?" @endif
                                @class(['rounded-full px-3 py-1.5 text-xs', 'bg-leaf-500 text-white' => $viewing->status === $k, 'bg-sand' => $viewing->status !== $k])>{{ $v }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
