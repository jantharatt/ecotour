{{-- ใช้ร่วมกันระหว่างหน้าคำสั่งซื้อและหน้าการจอง: ต้องมี $amount --}}
<div class="card space-y-4 p-6">
    <h2 class="text-lg font-semibold">💳 ชำระเงิน</h2>
    <div class="flex flex-wrap items-center gap-6 rounded-2xl bg-lagoon-50 p-4">
        {{-- วาง QR พร้อมเพย์จริงไว้ที่ public/images/promptpay.png --}}
        <img src="{{ file_exists(public_path('images/promptpay.png')) ? asset('images/promptpay.png') : asset('images/placeholders/promptpay.svg') }}"
             class="h-36 w-36 rounded-xl bg-white p-2"
             alt="{{ file_exists(public_path('images/promptpay.png')) ? 'QR พร้อมเพย์' : 'พื้นที่สำหรับแสดง QR พร้อมเพย์จริง' }}">
        <div class="text-sm">
            <p>ยอดชำระ <span class="price text-2xl">฿{{ number_format($amount, 2) }}</span></p>
            <p class="mt-1 text-muted">พร้อมเพย์: {{ config('services.promptpay.id', '0X-XXXX-XXXX') }}</p>
            <p class="text-muted">ชื่อบัญชี: {{ config('services.promptpay.name', 'วิสาหกิจชุมชน...') }}</p>
        </div>
    </div>
    <form wire:submit="uploadSlip" class="space-y-3">
        <label class="label">แนบสลิปการโอน</label>
        <input type="file" wire:model="slip" accept="image/*" class="input file:mr-3 file:rounded-full file:border-0 file:bg-leaf-100 file:px-4 file:py-1 file:text-leaf-700">
        <div wire:loading wire:target="slip" class="text-xs text-muted">กำลังอัปโหลด...</div>
        @if ($slip) <img src="{{ $slip->temporaryUrl() }}" class="h-40 rounded-xl" alt=""> @endif
        @error('slip') <p class="error">{{ $message }}</p> @enderror
        <button class="btn-primary">ส่งหลักฐานการชำระเงิน</button>
    </form>
</div>
