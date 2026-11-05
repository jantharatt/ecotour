<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'หลังบ้าน' }} · Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{ side: false }" class="min-h-screen">
@php
    $menu = [
        ['admin.dashboard', '📊', 'แดชบอร์ด'],
        ['admin.orders', '📦', 'คำสั่งซื้อ'],
        ['admin.products', '🧺', 'สินค้า'],
        ['admin.places', '📍', 'แหล่งท่องเที่ยว'],
        ['admin.communities', '🏡', 'ชุมชน'],
    ];
@endphp

<aside :class="side ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-40 w-64 transform bg-leaf-800 text-leaf-100 transition lg:translate-x-0">
    <div class="flex h-16 items-center gap-2 px-6 border-b border-leaf-700">
        <span class="text-xl">🌿</span>
        <span class="font-display font-semibold text-white">หลังบ้านชุมชน</span>
    </div>
    <nav class="space-y-1 p-4 text-sm">
        @foreach ($menu as [$r, $icon, $label])
            <a href="{{ route($r) }}" wire:navigate
               @class([
                   'flex items-center gap-3 rounded-xl px-4 py-2.5 transition',
                   'bg-leaf-600 text-white' => request()->routeIs($r),
                   'hover:bg-leaf-700' => ! request()->routeIs($r),
               ])>
                <span>{{ $icon }}</span>{{ $label }}
            </a>
        @endforeach
        <div class="my-4 border-t border-leaf-700"></div>
        <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 hover:bg-leaf-700">🌐 ดูหน้าเว็บไซต์</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 hover:bg-leaf-700">🚪 ออกจากระบบ</button>
        </form>
    </nav>
</aside>
<div x-show="side" @click="side=false" class="fixed inset-0 z-30 bg-black/30 lg:hidden" style="display:none"></div>

<div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-sand bg-cream/90 px-4 backdrop-blur sm:px-8">
        <button @click="side=true" class="lg:hidden rounded-full p-2 hover:bg-leaf-50">☰</button>
        <h1 class="font-display text-lg font-semibold">{{ $title ?? '' }}</h1>
        <span class="text-sm text-muted">{{ auth()->user()->name }}</span>
    </header>
    <main class="p-4 sm:p-8">
        {{ $slot }}
    </main>
</div>

<div x-data="{ show: false, message: '' }"
     x-on:toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 2500)"
     x-show="show" x-transition style="display:none"
     class="fixed bottom-6 right-6 z-50 rounded-2xl bg-leaf-700 px-5 py-3 text-sm text-white shadow-lg" x-text="message"></div>

@livewireScripts
</body>
</html>
