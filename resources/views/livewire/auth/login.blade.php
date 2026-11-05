<div class="container-page grid min-h-[70vh] place-items-center py-10 sm:py-16">
    <form wire:submit="login" class="w-full max-w-xl space-y-7 rounded-3xl border border-sand bg-white p-6 shadow-soft sm:p-10">
        <div class="text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-leaf-50 text-3xl" aria-hidden="true">🌿</span>
            <h1 class="mt-5 font-display text-3xl font-semibold">เข้าสู่ระบบ</h1>
            <p class="mt-2 text-base leading-7 text-muted">เข้าสู่บัญชีเพื่อสั่งซื้อสินค้าและติดตามคำสั่งซื้อของคุณ</p>
        </div>

        <div>
            <label for="email" class="mb-2 block text-base font-semibold">อีเมล</label>
            <input id="email" name="email" type="email" wire:model="email" autocomplete="email" inputmode="email" required
                   aria-describedby="email-error" class="input min-h-14 text-lg">
            @error('email') <p id="email-error" role="alert" class="mt-2 text-base font-medium text-red-700">{{ $message }}</p> @enderror
        </div>

        <div x-data="{ visible: false }">
            <label for="password" class="mb-2 block text-base font-semibold">รหัสผ่าน</label>
            <div class="relative">
                <input id="password" name="password" :type="visible ? 'text' : 'password'" wire:model="password" autocomplete="current-password" required
                       aria-describedby="password-error" class="input min-h-14 pr-24 text-lg">
                <button type="button" @click="visible = !visible" :aria-pressed="visible" class="absolute inset-y-0 right-2 my-1 rounded-xl px-3 text-sm font-semibold text-leaf-800 underline hover:bg-leaf-50">
                    <span x-text="visible ? 'ซ่อน' : 'แสดง'"></span>
                </button>
            </div>
            @error('password') <p id="password-error" role="alert" class="mt-2 text-base font-medium text-red-700">{{ $message }}</p> @enderror
        </div>

        <label class="flex min-h-12 cursor-pointer items-center gap-3 text-base">
            <input type="checkbox" wire:model="remember" class="h-6 w-6 rounded accent-leaf-700">
            <span>จดจำฉันในอุปกรณ์นี้</span>
        </label>

        <button class="btn-primary min-h-14 w-full py-3 text-lg" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">เข้าสู่ระบบ</span>
            <span wire:loading wire:target="login">กำลังเข้าสู่ระบบ...</span>
        </button>

        <div class="border-t border-sand pt-5 text-center">
            <p class="text-base text-muted">ยังไม่มีบัญชีใช่ไหม?</p>
            <a href="{{ route('register') }}" wire:navigate class="mt-2 inline-flex min-h-12 items-center justify-center px-4 font-semibold text-leaf-800 underline decoration-2 underline-offset-4 hover:text-leaf-600">สมัครสมาชิกที่นี่</a>
        </div>
    </form>
</div>
