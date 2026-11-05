<div>
    <section class="relative overflow-hidden bg-leaf-900 text-white">
        <div class="absolute -right-20 -top-32 h-96 w-96 rounded-full border border-white/10"></div>
        <div class="absolute -right-8 -top-20 h-72 w-72 rounded-full border border-white/10"></div>
        <div class="container-page relative py-14 sm:py-20">
            <p class="text-sm font-semibold uppercase tracking-[.2em] text-sun-200">People make a place</p>
            <h1 class="mt-4 max-w-3xl font-display text-4xl font-semibold leading-tight text-white sm:text-5xl">ชุมชนริมน้ำ<br><span class="text-sun-200">ที่อยากชวนให้รู้จัก</span></h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-white/70">เบื้องหลังศาสนสถานและเส้นทางน้ำ คือผู้คนที่สืบสานวิถี ความเชื่อ และความทรงจำของอยุธยา มาทำความรู้จักบ้านของพวกเขากัน</p>
            <a href="#community-list" class="mt-7 inline-flex items-center gap-2 font-semibold text-white hover:text-sun-200">เลือกชุมชนที่จะไปเยือน <span aria-hidden="true">↓</span></a>
        </div>
    </section>

    <section id="community-list" class="container-page py-12 sm:py-16">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">Three neighborhoods, many stories</p>
                <h2 class="mt-2 font-display text-3xl font-semibold">เลือกฟังเรื่องเล่าจากพื้นที่</h2>
            </div>
            <a href="{{ route('story') }}" wire:navigate class="font-semibold text-leaf-700 hover:underline">อ่านเรื่อง 3 สายน้ำ 3 ศาสนา →</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($communities as $community)
                <article class="group overflow-hidden rounded-3xl border border-sand bg-white transition duration-300 hover:-translate-y-1 hover:shadow-soft">
                    <a href="{{ route('communities.show', $community) }}" wire:navigate class="relative block overflow-hidden">
                        <img src="{{ $community->cover_url }}" alt="ภาพประกอบ{{ $community->name }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                        <span class="absolute bottom-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-leaf-800">📍 {{ $community->location_text ?: 'อยุธยา' }}</span>
                    </a>
                    <div class="p-6">
                        <p class="text-xs font-semibold uppercase tracking-[.16em] text-lagoon-700">เรื่องเล่าจากชุมชน</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold group-hover:text-leaf-700">
                            <a href="{{ route('communities.show', $community) }}" wire:navigate>{{ $community->name }}</a>
                        </h2>
                        @if ($community->tagline)
                            <p class="mt-2 font-medium text-sun-700">{{ $community->tagline }}</p>
                        @endif
                        <p class="mt-3 line-clamp-4 text-sm leading-7 text-muted">{{ $community->description }}</p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="badge-leaf">📍 {{ $community->attractions_count }} จุดแวะ</span>
                            <span class="badge-sun">🧺 {{ $community->products_count }} ผลิตภัณฑ์</span>
                        </div>
                        <a href="{{ route('communities.show', $community) }}" wire:navigate class="mt-6 inline-flex items-center gap-2 font-semibold text-leaf-700 hover:text-leaf-900">รู้จักชุมชนนี้ <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="container-page pb-16">
        <div class="rounded-3xl bg-[#f0eee4] p-7 sm:p-10">
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-leaf-700">Travel with respect</p>
            <h2 class="mt-3 font-display text-2xl font-semibold">มาเยือนอย่างเพื่อนบ้าน</h2>
            <p class="mt-3 max-w-3xl leading-7 text-muted">เปิดใจเรียนรู้วิถีของแต่ละชุมชน เคารพพื้นที่ศาสนกิจและความเป็นส่วนตัว และช่วยกันดูแลสายน้ำที่เป็นบ้านของทุกคน</p>
        </div>
    </section>
</div>
