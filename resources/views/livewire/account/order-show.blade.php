<div class="container-page max-w-4xl py-8">
    <a href="{{ route('account', ['tab' => 'orders']) }}" wire:navigate class="text-sm text-muted hover:text-leaf-700">← คำสั่งซื้อทั้งหมด</a>
    <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold">คำสั่งซื้อ {{ $order->code }}</h1>
        @include('partials.status-badge', ['status' => $order->status, 'label' => $order->status_label])
    </div>

    {{-- timeline สถานะ --}}
    @php($steps = ['pending', 'paid', 'packing', 'shipped', 'completed'])
    @if ($order->status !== 'cancelled')
        <ol class="mt-6 grid grid-cols-5 gap-1 text-center text-[11px] sm:text-xs">
            @foreach ($steps as $s)
                @php($done = array_search($order->status, $steps) >= $loop->index)
                <li>
                    <div @class(['mx-auto h-2 rounded-full', 'bg-leaf-500' => $done, 'bg-sand' => !$done])></div>
                    <p @class(['mt-1', 'text-leaf-700 font-medium' => $done, 'text-muted' => !$done])>{{ \App\Models\Order::STATUSES[$s] }}</p>
                </li>
            @endforeach
        </ol>
    @endif

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="card p-6">
            <h2 class="font-semibold">รายการสินค้า</h2>
            <div class="mt-3 divide-y divide-sand text-sm">
                @foreach ($order->items as $item)
                    <div class="flex justify-between py-2"><span>{{ $item->product_name }} × {{ $item->qty }}</span><span>฿{{ number_format($item->line_total) }}</span></div>
                @endforeach
                <div class="flex justify-between py-2 text-muted"><span>ค่าจัดส่ง</span><span>฿{{ number_format($order->shipping_fee) }}</span></div>
                <div class="flex justify-between py-2 font-semibold"><span>รวม</span><span class="price">฿{{ number_format($order->total) }}</span></div>
            </div>
        </div>
        <div class="card p-6 text-sm">
            <h2 class="font-semibold">จัดส่งถึง</h2>
            <p class="mt-2">{{ $order->ship_name }} · {{ $order->ship_phone }}</p>
            <p class="text-muted">{{ $order->ship_address }}</p>
            <p class="mt-3">ชำระโดย: {{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] }}</p>
            @if ($order->tracking_no)
                <p class="mt-3 rounded-xl bg-leaf-50 p-3">🚚 เลขพัสดุ: <span class="font-semibold">{{ $order->tracking_no }}</span></p>
            @endif
        </div>
    </div>

    @if ($order->status === 'pending' && $order->payment_method !== 'cod')
        <div class="mt-6">@include('partials.slip-upload', ['amount' => $order->total])</div>
    @endif
    @if ($order->payment_slip)
        <div class="mt-6 card p-6"><p class="font-semibold">สลิปที่แนบ</p><img src="{{ asset('storage/'.$order->payment_slip) }}" class="mt-3 h-60 rounded-xl" alt=""></div>
    @endif
    @if ($order->status === 'pending')
        <button wire:click="cancel" wire:confirm="ยืนยันยกเลิกคำสั่งซื้อ?" class="mt-6 text-sm text-red-600 hover:underline">ยกเลิกคำสั่งซื้อ</button>
    @endif
</div>
