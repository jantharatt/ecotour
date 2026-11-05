<div class="container-page py-8">
    <h1 class="text-3xl font-bold">ตะกร้าสินค้า</h1>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-[2rem] bg-white p-16 text-center">
            <p class="text-5xl">🧺</p>
            <p class="mt-4 text-muted">ตะกร้ายังว่างอยู่</p>
            <a href="{{ route('shop.index') }}" wire:navigate class="btn-primary mt-6">เลือกซื้อของฝาก</a>
        </div>
    @else
        <div class="mt-6 grid gap-8 lg:grid-cols-3">
            <div class="card divide-y divide-sand lg:col-span-2">
                @foreach ($items as $item)
                    @php($p = $item['product'])
                    <div class="flex gap-4 p-4" wire:key="cart-{{ $p->id }}">
                        <img src="{{ $p->image_url }}" class="h-20 w-20 rounded-xl object-cover" alt="">
                        <div class="flex-1">
                            <a href="{{ route('shop.show', $p) }}" wire:navigate class="font-medium hover:text-leaf-700">{{ $p->name }}</a>
                            <p class="text-sm text-leaf-700">
                                จากชุมชน
                                <a href="{{ route('communities.show', $p->community) }}" wire:navigate class="font-semibold underline decoration-leaf-300 underline-offset-2 hover:text-leaf-900">{{ $p->community->name }}</a>
                            </p>
                            <p class="text-sm text-muted">฿{{ number_format($p->price) }} / {{ $p->unit }}</p>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="flex items-center rounded-full ring-1 ring-sand">
                                    <button wire:click="updateQty({{ $p->id }}, {{ $item['qty'] - 1 }})" class="h-8 w-8 rounded-full hover:bg-leaf-50">−</button>
                                    <span class="w-8 text-center text-sm">{{ $item['qty'] }}</span>
                                    <button wire:click="updateQty({{ $p->id }}, {{ $item['qty'] + 1 }})" class="h-8 w-8 rounded-full hover:bg-leaf-50"
                                            @disabled($item['qty'] >= $p->stock)>＋</button>
                                </div>
                                <button wire:click="remove({{ $p->id }})" class="text-xs text-muted hover:text-red-600">ลบ</button>
                            </div>
                        </div>
                        <p class="price">฿{{ number_format($item['line_total']) }}</p>
                    </div>
                @endforeach
            </div>

            <aside class="card h-fit space-y-3 p-6">
                <h2 class="text-lg font-semibold">สรุปคำสั่งซื้อ</h2>
                <div class="flex justify-between text-sm"><span class="text-muted">ยอดสินค้า</span><span>฿{{ number_format($subtotal) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-muted">ค่าจัดส่ง</span>
                    <span>{{ $shipping > 0 ? '฿'.number_format($shipping) : 'ฟรี' }}</span></div>
                @if ($subtotal < $freeMin)
                    @php($pct = min(100, $subtotal / $freeMin * 100))
                    <div class="rounded-xl bg-leaf-50 p-3 text-xs text-leaf-800">
                        ซื้ออีก ฿{{ number_format($freeMin - $subtotal) }} ส่งฟรี!
                        <div class="mt-2 h-1.5 rounded-full bg-leaf-100"><div class="h-1.5 rounded-full bg-leaf-500" style="width: {{ $pct }}%"></div></div>
                    </div>
                @endif
                <div class="flex justify-between border-t border-sand pt-3"><span class="font-semibold">รวมทั้งหมด</span>
                    <span class="price text-2xl">฿{{ number_format($total) }}</span></div>
                <a href="{{ route('checkout') }}" wire:navigate class="btn-accent w-full py-3">ดำเนินการชำระเงิน</a>
                <a href="{{ route('shop.index') }}" wire:navigate class="block text-center text-sm text-leaf-700 hover:underline">← เลือกสินค้าต่อ</a>
            </aside>
        </div>
    @endif
</div>
