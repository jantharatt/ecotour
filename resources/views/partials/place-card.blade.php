{{-- การ์ดแหล่งท่องเที่ยว: ต้องมี $place, เลือกใส่ $showDistance --}}
<article class="group flex flex-col overflow-hidden rounded-3xl border border-sand bg-white transition duration-300 hover:-translate-y-1 hover:shadow-soft">
    <a href="{{ route('places.show', $place) }}" wire:navigate class="relative block aspect-[16/10] overflow-hidden bg-leaf-50">
        <img src="{{ $place->image_url }}" alt="{{ $place->name }}" loading="lazy"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-ink/35 via-transparent to-transparent"></div>
        <div class="absolute left-3 top-3 flex gap-1">
            @if ($place->faith)
                <span class="badge bg-white/90 text-ink">{{ \App\Models\Attraction::FAITHS[$place->faith][1] }} ศาสนา{{ $place->faith_label }}</span>
            @else
                <span class="badge bg-white/90 text-ink">{{ $place->type_icon }} {{ $place->type_label }}</span>
            @endif
        </div>
        @unless ($place->image)
            <span class="absolute bottom-3 left-3 rounded-full bg-ink/65 px-3 py-1 text-[11px] text-white">ภาพประกอบ · รอภาพจริง</span>
        @endunless
        @if (isset($place->distance_km))
            <span class="badge absolute right-3 top-3 bg-lagoon-600 text-white">📍 {{ number_format($place->distance_km, 1) }} กม.</span>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-[.12em] text-lagoon-700">{{ $place->community->name }}</p>
        <a href="{{ route('places.show', $place) }}" wire:navigate class="mt-2 font-display text-xl font-semibold leading-snug hover:text-leaf-700">{{ $place->name }}</a>
        <p class="mt-2 line-clamp-2 text-sm leading-6 text-muted">{{ $place->summary }}</p>
        <div class="mt-auto flex gap-2 pt-5">
            @if ($place->lat)
                <button type="button" x-data x-on:click="$dispatch('focus-marker', { id: {{ $place->id }} })" class="btn-soft btn-sm flex-1">🗺️ ดูบนแผนที่</button>
            @endif
            @if ($place->directions_url)
                <a href="{{ $place->directions_url }}" target="_blank" rel="noopener" class="btn-accent btn-sm flex-1">นำทาง ↗</a>
            @endif
        </div>
    </div>
</article>
