<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ท่องเที่ยวชุมชน' }} · {{ config('app.name', '3 สายน้ำ 3 ศาสนา') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex min-h-screen flex-col">

{{-- ===== Header ===== --}}
<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-sand/80 bg-white/95 backdrop-blur-xl">
    <div class="container-page flex h-[4.5rem] items-center justify-between gap-4">
        <a href="{{ route('home') }}" wire:navigate class="flex shrink-0 items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-leaf-900 text-lg text-white">🌊</span>
            <span class="font-display text-base font-semibold leading-tight text-leaf-900 sm:text-lg">
                3 สายน้ำ<br class="sm:hidden"> <span class="text-sun-600">3 ศาสนา</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 text-base font-medium xl:flex">
            @php
                $links = [
                    ['home', 'หน้าแรก'],
                    ['story', '3 สายน้ำ 3 ศาสนา'],
                    ['places.index', 'แหล่งท่องเที่ยว & แผนที่'],
                    ['communities.index', 'ชุมชน'],
                    ['shop.index', 'ร้านของฝาก'],
                ];
            @endphp
            @foreach ($links as [$r, $label])
                <a href="{{ route($r) }}" wire:navigate
                   @class([
                       'min-h-11 rounded-full px-3 py-2 transition',
                       'bg-leaf-50 text-leaf-800' => request()->routeIs($r === 'home' ? 'home' : str_replace('.index', '.*', $r)),
                       'text-muted hover:bg-leaf-50 hover:text-leaf-800' => ! request()->routeIs($r === 'home' ? 'home' : str_replace('.index', '.*', $r)),
                   ])>{{ $label }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <livewire:cart.counter />
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account') }}" wire:navigate
                   class="hidden min-h-11 items-center rounded-full border border-sand px-4 py-2 text-base font-semibold text-leaf-800 transition hover:bg-leaf-50 sm:inline-flex">
                    บัญชีของฉัน
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button class="min-h-11 rounded-full px-4 py-2 text-base font-semibold text-leaf-800 transition hover:bg-leaf-50">ออกจากระบบ</button>
                </form>
            @else
                <a href="{{ route('login') }}" wire:navigate class="hidden min-h-11 items-center rounded-full bg-leaf-800 px-5 py-2 text-base font-semibold text-white transition hover:bg-leaf-900 sm:inline-flex">เข้าสู่ระบบ</a>
                <a href="{{ route('register') }}" wire:navigate class="hidden min-h-11 items-center rounded-full border border-leaf-700 px-5 py-2 text-base font-semibold text-leaf-800 transition hover:bg-leaf-50 sm:inline-flex">สมัครสมาชิก</a>
            @endauth
            <button @click="open = !open" :aria-expanded="open.toString()" :aria-label="open ? 'ปิดเมนู' : 'เปิดเมนู'" class="grid h-12 w-12 place-items-center rounded-full text-leaf-900 hover:bg-leaf-50 xl:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- mobile nav --}}
    <div x-show="open" x-collapse class="border-t border-sand bg-white xl:hidden">
        <nav class="container-page flex flex-col gap-1 py-3 text-base font-medium">
            <a href="{{ route('home') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">หน้าแรก</a>
            <a href="{{ route('story') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">เรื่องราว 3 สายน้ำ 3 ศาสนา</a>
            <a href="{{ route('places.index') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">แหล่งท่องเที่ยวและแผนที่</a>
            <a href="{{ route('communities.index') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">ชุมชน</a>
            <a href="{{ route('shop.index') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">ร้านของฝาก</a>
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account') }}" wire:navigate @click="open = false" class="min-h-12 rounded-xl px-4 py-3 hover:bg-leaf-50">บัญชีของฉันและคำสั่งซื้อ</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button class="min-h-12 w-full rounded-xl border border-leaf-700 px-4 py-3 text-left font-semibold text-leaf-800 hover:bg-leaf-50">ออกจากระบบ</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('login') }}" wire:navigate @click="open = false" class="flex min-h-12 items-center justify-center rounded-xl bg-leaf-800 px-4 py-3 font-semibold text-white">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" wire:navigate @click="open = false" class="flex min-h-12 items-center justify-center rounded-xl border border-leaf-700 px-4 py-3 font-semibold text-leaf-800">สมัครสมาชิก</a>
                </div>
            @endauth
        </nav>
    </div>
</header>

{{-- ===== Flash ===== --}}
@if (session('success'))
    <div class="container-page mt-4">
        <div class="rounded-2xl bg-leaf-50 px-4 py-3 text-sm text-leaf-800 ring-1 ring-leaf-200">✅ {{ session('success') }}</div>
    </div>
@endif

<main class="flex-1">
    {{ $slot }}
</main>

{{-- ===== Footer ===== --}}
<footer class="mt-20 bg-leaf-800 text-leaf-100">
    <div class="container-page grid gap-10 py-12 text-sm md:grid-cols-4">
        <div class="md:col-span-2">
            <p class="font-display text-xl font-semibold text-white">3 สายน้ำ <span class="text-sun-300">3 ศาสนา</span></p>
            <p class="mt-3 max-w-md leading-7 text-leaf-100/80">
                พื้นที่เล่าเรื่องของสายน้ำ ศรัทธา และผู้คนในชุมชนอยุธยา เดินทางอย่างเข้าใจและสนับสนุนเจ้าของบ้าน
            </p>
            <a href="{{ route('story') }}" wire:navigate class="mt-5 inline-flex font-semibold text-sun-200 hover:text-white">อ่านเรื่องราวของเรา →</a>
        </div>
        <div>
            <p class="font-semibold text-white">เมนู</p>
            <ul class="mt-3 space-y-2 text-leaf-100/80">
                <li><a href="{{ route('places.index') }}" class="hover:text-white">แหล่งท่องเที่ยว & แผนที่</a></li>
                <li><a href="{{ route('communities.index') }}" class="hover:text-white">ชุมชน</a></li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-white">ร้านของฝาก</a></li>
                <li><a href="{{ route('cart') }}" class="hover:text-white">ตะกร้าสินค้า</a></li>
            </ul>
        </div>
        <div>
            <p class="font-semibold text-white">มาเยือนด้วยความเคารพ</p>
            <ul class="mt-3 space-y-2 leading-6 text-leaf-100/80">
                <li>แต่งกายสุภาพเมื่อเข้าศาสนสถาน</li>
                <li>เคารพวิถีและความเป็นส่วนตัว</li>
                <li>ช่วยกันดูแลสายน้ำและชุมชน</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-leaf-700 py-4 text-center text-xs text-leaf-300">© {{ date('Y') }} Community Eco-Tourism</div>
</footer>

{{-- ===== Toast ===== --}}
<div x-data="{ show: false, message: '' }"
     x-on:toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 2500)"
     x-show="show" x-transition
     class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-full bg-ink px-5 py-3 text-sm text-white shadow-lg"
     style="display:none" x-text="message"></div>

@livewireScripts
</body>
</html>
