@props(['model', 'title' => '', 'maxWidth' => 'max-w-2xl'])

<div x-data="{ open: $wire.entangle('{{ $model }}') }" x-show="open" x-cloak
     x-on:keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto" style="display:none">
    <div class="fixed inset-0 bg-ink/40" x-on:click="open = false" x-transition.opacity></div>
    <div class="relative mx-auto my-10 w-full {{ $maxWidth }} px-4" x-transition>
        <div class="card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{ $title }}</h3>
                <button type="button" x-on:click="open = false" class="rounded-full p-1 text-muted hover:bg-sand">✕</button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
