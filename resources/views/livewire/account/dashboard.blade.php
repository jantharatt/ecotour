<div class="container-page max-w-4xl py-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-semibold">สวัสดี, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-base text-muted">ติดตามคำสั่งซื้อสินค้าและดูสถานะรายการของคุณ</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-outline min-h-12 px-5 text-base">ออกจากระบบ</button>
        </form>
    </div>

    <div class="mt-6 space-y-3">
        @forelse ($orders as $o)
            <a href="{{ route('account.order', $o) }}" wire:navigate class="card flex flex-wrap items-center justify-between gap-3 p-5 hover:ring-leaf-300">
                <div>
                    <p class="font-semibold">{{ $o->code }}</p>
                    <p class="text-sm text-muted">{{ $o->created_at->locale('th')->translatedFormat('j M Y H:i') }} · {{ $o->items_count }} รายการ</p>
                </div>
                <div class="text-right">
                    @include('partials.status-badge', ['status' => $o->status, 'label' => $o->status_label])
                    <p class="price mt-1">฿{{ number_format($o->total) }}</p>
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-sand bg-white p-10 text-center">
                <p class="font-display text-xl font-semibold">ยังไม่มีคำสั่งซื้อ</p>
                <p class="mt-2 text-base text-muted">เลือกชมของฝากจากชุมชนได้ที่ร้านค้า</p>
                <a href="{{ route('shop.index') }}" wire:navigate class="btn-primary mt-5 min-h-12 px-6 text-base">ไปที่ร้านของฝาก</a>
            </div>
        @endforelse
    </div>
</div>
