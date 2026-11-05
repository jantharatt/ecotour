<div class="container-page py-8">
    <h1 class="text-3xl font-bold">ชำระเงิน</h1>

    <form wire:submit="placeOrder" class="mt-6 grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-4 p-6">
                <h2 class="text-lg font-semibold">📦 ที่อยู่จัดส่ง</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">ชื่อผู้รับ</label>
                        <input wire:model="ship_name" class="input">
                        @error('ship_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">เบอร์โทร</label>
                        <input wire:model="ship_phone" class="input" inputmode="tel">
                        @error('ship_phone') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="label">ที่อยู่</label>
                    <textarea wire:model="ship_address" rows="3" class="input" placeholder="บ้านเลขที่ ถนน ตำบล อำเภอ จังหวัด รหัสไปรษณีย์"></textarea>
                    @error('ship_address') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="card space-y-3 p-6">
                <h2 class="text-lg font-semibold">💳 วิธีชำระเงิน</h2>
                @foreach (\App\Models\Order::PAYMENT_METHODS as $key => $label)
                    <label @class(['flex cursor-pointer items-center gap-3 rounded-2xl p-4 ring-1 transition',
                                   'bg-leaf-50 ring-leaf-400' => $payment_method === $key,
                                   'ring-sand hover:bg-cream' => $payment_method !== $key])>
                        <input type="radio" wire:model.live="payment_method" value="{{ $key }}" class="accent-leaf-500">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
                <p class="text-xs text-muted">หลังสั่งซื้อ สามารถแนบสลิปได้ที่หน้ารายละเอียดคำสั่งซื้อ</p>
            </div>
        </div>

        <aside class="card h-fit space-y-3 p-6">
            <h2 class="text-lg font-semibold">รายการสินค้า</h2>
            @foreach ($items as $i)
                <div class="flex justify-between gap-2 text-sm">
                    <span>{{ $i['product']->name }} <span class="block text-xs text-muted">จาก{{ $i['product']->community->name }}</span>× {{ $i['qty'] }}</span>
                    <span>฿{{ number_format($i['line_total']) }}</span>
                </div>
            @endforeach
            <div class="space-y-1 border-t border-sand pt-3 text-sm">
                <div class="flex justify-between"><span class="text-muted">ยอดสินค้า</span><span>฿{{ number_format($subtotal) }}</span></div>
                <div class="flex justify-between"><span class="text-muted">ค่าจัดส่ง</span><span>{{ $shipping ? '฿'.number_format($shipping) : 'ฟรี' }}</span></div>
            </div>
            <div class="flex justify-between border-t border-sand pt-3"><span class="font-semibold">รวม</span><span class="price text-2xl">฿{{ number_format($total) }}</span></div>
            @error('stock') <p class="error">{{ $message }}</p> @enderror
            <button class="btn-accent w-full py-3" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="placeOrder">ยืนยันการสั่งซื้อ</span>
                <span wire:loading wire:target="placeOrder">กำลังดำเนินการ...</span>
            </button>
        </aside>
    </form>
</div>
