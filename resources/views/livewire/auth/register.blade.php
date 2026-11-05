<div class="container-page grid min-h-[70vh] place-items-center py-10 sm:py-16">
    <form wire:submit="register" class="w-full max-w-xl space-y-5 rounded-3xl border border-sand bg-white p-6 shadow-soft sm:p-10" x-data="{ passwordsVisible: false }">
        <div class="text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-sun-50 text-3xl" aria-hidden="true">🤝</span>
            <h1 class="mt-5 font-display text-3xl font-semibold">สมัครสมาชิก</h1>
            <p class="mt-2 text-base leading-7 text-muted">สร้างบัญชีเพื่อสั่งซื้อของชุมชนและติดตามรายการของคุณ</p>
        </div>

        @foreach ([
            ['name', 'ชื่อ-นามสกุล', 'text', 'name'],
            ['email', 'อีเมล', 'email', 'email'],
            ['phone', 'เบอร์โทรศัพท์ (ไม่บังคับ)', 'tel', 'tel'],
        ] as [$field, $label, $type, $autocomplete])
            <div>
                <label for="{{ $field }}" class="mb-2 block text-base font-semibold">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" wire:model="{{ $field }}" autocomplete="{{ $autocomplete }}"
                       @if ($field !== 'phone') required @endif
                       @if ($field === 'phone') inputmode="tel" @endif
                       aria-describedby="{{ $field }}-error" class="input min-h-14 text-lg">
                @error($field) <p id="{{ $field }}-error" role="alert" class="mt-2 text-base font-medium text-red-700">{{ $message }}</p> @enderror
            </div>
        @endforeach

        @foreach ([
            ['password', 'รหัสผ่าน (อย่างน้อย 8 ตัว)', 'new-password'],
            ['password_confirmation', 'ยืนยันรหัสผ่าน', 'new-password'],
        ] as [$field, $label, $autocomplete])
            <div>
                <label for="{{ $field }}" class="mb-2 block text-base font-semibold">{{ $label }}</label>
                <div class="relative">
                    <input id="{{ $field }}" name="{{ $field }}" :type="passwordsVisible ? 'text' : 'password'" wire:model="{{ $field }}" autocomplete="{{ $autocomplete }}" required
                           aria-describedby="{{ $field }}-error" class="input min-h-14 pr-24 text-lg">
                    <button type="button" @click="passwordsVisible = !passwordsVisible" :aria-pressed="passwordsVisible" class="absolute inset-y-0 right-2 my-1 rounded-xl px-3 text-sm font-semibold text-leaf-800 underline hover:bg-leaf-50">
                        <span x-text="passwordsVisible ? 'ซ่อน' : 'แสดง'"></span>
                    </button>
                </div>
                @error($field) <p id="{{ $field }}-error" role="alert" class="mt-2 text-base font-medium text-red-700">{{ $message }}</p> @enderror
            </div>
        @endforeach

        <button class="btn-primary min-h-14 w-full py-3 text-lg" wire:loading.attr="disabled" wire:target="register">
            <span wire:loading.remove wire:target="register">สมัครสมาชิก</span>
            <span wire:loading wire:target="register">กำลังสร้างบัญชี...</span>
        </button>

        <div class="border-t border-sand pt-5 text-center">
            <p class="text-base text-muted">มีบัญชีอยู่แล้วใช่ไหม?</p>
            <a href="{{ route('login') }}" wire:navigate class="mt-2 inline-flex min-h-12 items-center justify-center px-4 font-semibold text-leaf-800 underline decoration-2 underline-offset-4 hover:text-leaf-600">เข้าสู่ระบบที่นี่</a>
        </div>
    </form>
</div>
