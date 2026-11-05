<div>
    <section class="relative isolate overflow-hidden bg-leaf-900 text-white">
        <div class="absolute inset-0 -z-10 opacity-20">
            <div class="absolute -right-24 -top-32 h-96 w-96 rounded-full border border-white/40"></div>
            <div class="absolute -right-12 -top-20 h-72 w-72 rounded-full border border-white/30"></div>
            <div class="absolute -bottom-48 left-1/3 h-96 w-[70rem] -rotate-6 rounded-[50%] border-[3rem] border-lagoon-400/20"></div>
        </div>

        <div class="container-page grid min-h-[560px] items-center gap-12 py-16 lg:grid-cols-[1.05fr_.95fr] lg:py-20">
            <div class="max-w-2xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-leaf-100">
                    <span class="h-2 w-2 rounded-full bg-sun-300"></span>
                    เที่ยวชุมชนอยุธยา · เดินทางด้วยหัวใจ
                </p>
                <h1 class="mt-7 font-display text-5xl font-semibold leading-[1.15] text-white sm:text-6xl lg:text-7xl">
                    สามสายน้ำ<br>
                    <span class="text-sun-300">สามศรัทธา</span><br>
                    หนึ่งบ้านของเรา
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-white/75">
                    ชวนออกเดินทางไปฟังเรื่องเล่าริมน้ำ รู้จักวิถีของชุมชน และสัมผัสการอยู่ร่วมกันของผู้คนต่างศาสนาในอยุธยา
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('places.index') }}" wire:navigate class="btn-accent px-6 py-3 text-base">เริ่มออกเดินทาง <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('communities.index') }}" wire:navigate class="btn rounded-full border border-white/30 px-6 py-3 text-base text-white hover:bg-white/10">รู้จักชุมชน</a>
                </div>
                <div class="mt-12 flex flex-wrap gap-x-8 gap-y-3 border-t border-white/15 pt-6 text-sm text-white/70">
                    <span><strong class="mr-2 font-display text-2xl text-white">3</strong>สายน้ำโอบล้อมเกาะเมือง</span>
                    <span><strong class="mr-2 font-display text-2xl text-white">3</strong>ศาสนาอยู่ร่วมกัน</span>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl">
                <div class="absolute -inset-4 rotate-3 rounded-[2rem] bg-sun-300/20"></div>
                <div class="relative overflow-hidden rounded-[1.75rem] border border-white/20 bg-leaf-800 shadow-2xl">
                    <img src="{{ asset('images/placeholders/ayutthaya.svg') }}" alt="ภาพประกอบเมืองริมสายน้ำ" class="aspect-[1.08] w-full object-cover">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/95 via-leaf-900/80 to-transparent p-6 pt-24 text-white sm:p-8 sm:pt-28">
                        <p class="text-xs font-semibold uppercase tracking-[.24em] text-sun-200">A living heritage</p>
                        <p class="mt-2 font-display text-2xl font-semibold sm:text-3xl">มากกว่าเมืองเก่า<br>คือบ้านของผู้คน</p>
                        <a href="{{ route('story') }}" wire:navigate class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-sun-200">อ่านเรื่องราวของสายน้ำ <span aria-hidden="true">→</span></a>
                    </div>
                    <div class="absolute right-4 top-4 rounded-2xl border border-white/30 bg-white/90 px-4 py-3 text-leaf-900 shadow-lg">
                        <p class="text-xs text-muted">จังหวัดพระนครศรีอยุธยา</p>
                        <p class="font-display font-semibold">เที่ยวช้า ๆ ได้เรื่องราว</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-page py-16 sm:py-20">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">The rivers that shaped us</p>
                <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">เมืองที่มีสายน้ำ<br>เป็นทั้งทางผ่านและบ้าน</h2>
            </div>
            <p class="max-w-2xl text-base leading-7 text-muted">
                แม่น้ำเจ้าพระยา แม่น้ำป่าสัก และแม่น้ำลพบุรีหล่อเลี้ยงการเดินทาง การค้าขาย และชีวิตประจำวัน
                เรื่องราวของอยุธยาจึงผูกพันกับน้ำและผู้คนสองฝั่งมาอย่างยาวนาน
            </p>
        </div>

        <div class="mt-9 grid gap-4 md:grid-cols-3">
            @foreach ([
                ['เจ้าพระยา', 'สายน้ำสายใหญ่ที่เชื่อมผู้คนและชุมชนเข้าด้วยกัน', 'text-lagoon-700', 'bg-lagoon-100', '01'],
                ['ป่าสัก', 'เส้นทางน้ำฝั่งตะวันออกของเกาะเมือง', 'text-leaf-700', 'bg-leaf-100', '02'],
                ['ลพบุรี', 'สายน้ำทางเหนือที่โอบล้อมเมืองเก่า', 'text-sun-700', 'bg-sun-100', '03'],
            ] as [$river, $description, $color, $tint, $number])
                <article class="group rounded-3xl border border-sand bg-white p-6 transition hover:-translate-y-1 hover:shadow-soft sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl {{ $tint }} {{ $color }}" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.8"><path d="M3 7c3.5 0 3.5 3 7 3s3.5-3 7-3 3.5 3 4 3M3 14c3.5 0 3.5 3 7 3s3.5-3 7-3 3.5 3 4 3"/></svg>
                        </span>
                        <span class="font-display text-sm font-semibold text-muted/50">{{ $number }}</span>
                    </div>
                    <h3 class="mt-7 font-display text-2xl font-semibold">{{ $river }}</h3>
                    <p class="mt-2 leading-7 text-muted">{{ $description }}</p>
                </article>
            @endforeach
        </div>
        <a href="{{ route('story') }}" wire:navigate class="mt-6 inline-flex items-center gap-2 font-semibold text-leaf-700 hover:text-leaf-900">รู้จักภูมิหลังของสามสายน้ำ <span aria-hidden="true">→</span></a>
    </section>

    <section class="bg-[#f0eee4] py-16 sm:py-20">
        <div class="container-page">
            <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">Faiths beside the river</p>
                    <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">ต่างศรัทธา<br>แต่อยู่ร่วมบ้านเดียวกัน</h2>
                </div>
                <p class="max-w-2xl leading-7 text-muted">
                    พุทธ คริสต์ และอิสลาม มีร่องรอยและศาสนสถานในย่านชุมชนริมน้ำ
                    การเดินทางครั้งนี้ชวนมองให้ไกลกว่าสถาปัตยกรรม—ไปถึงผู้คน ความทรงจำ และการเคารพซึ่งกันและกัน
                </p>
            </div>

            <div class="mt-9 grid gap-4 md:grid-cols-3">
                @foreach (\App\Models\Attraction::FAITHS as $key => [$label, $icon, $color])
                    @php($place = $faithPlaces->firstWhere('faith', $key))
                    <article class="relative overflow-hidden rounded-3xl bg-white p-6 shadow-soft sm:p-7">
                        <div class="absolute inset-x-0 top-0 h-1.5" style="background: {{ $color }}"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-4xl" aria-hidden="true">{{ $icon }}</span>
                            <span class="text-xs font-semibold uppercase tracking-[.16em]" style="color: {{ $color }}">ศาสนา{{ $label }}</span>
                        </div>
                        @if ($place)
                            <h3 class="mt-5 font-display text-xl font-semibold">{{ $place->name }}</h3>
                            <p class="mt-2 min-h-20 text-sm leading-6 text-muted">{{ $place->summary }}</p>
                            <a href="{{ route('places.show', $place) }}" wire:navigate class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-leaf-700 hover:text-leaf-900">อ่านเรื่องราวและข้อมูลเยือน <span aria-hidden="true">→</span></a>
                        @else
                            <h3 class="mt-5 font-display text-xl font-semibold">ศรัทธาที่ร่วมสร้างชุมชน</h3>
                            <p class="mt-2 text-sm leading-6 text-muted">ติดตามเรื่องราวและสถานที่สำคัญของศาสนา{{ $label }}ในย่านชุมชน</p>
                        @endif
                    </article>
                @endforeach
            </div>
            <p class="mt-6 text-sm text-muted">เยี่ยมชมศาสนสถานด้วยความเคารพ แต่งกายสุภาพ และปฏิบัติตามคำแนะนำของแต่ละพื้นที่</p>
        </div>
    </section>

    <section class="container-page py-16 sm:py-20">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-leaf-700">Meet the neighbors</p>
                <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">รู้จักผู้คนก่อนรู้จักสถานที่</h2>
                <p class="mt-3 max-w-2xl leading-7 text-muted">แต่ละชุมชนมีที่มา วิถีชีวิต และสิ่งที่อยากแบ่งปันไม่เหมือนกัน เลือกชุมชนเพื่อฟังเรื่องเล่าจากพื้นที่</p>
            </div>
            <a href="{{ route('communities.index') }}" wire:navigate class="btn-outline">สำรวจทุกชุมชน <span aria-hidden="true">→</span></a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-3">
            @foreach ($communities as $community)
                <a href="{{ route('communities.show', $community) }}" wire:navigate class="group overflow-hidden rounded-3xl border border-sand bg-white transition hover:-translate-y-1 hover:shadow-soft">
                    <div class="relative overflow-hidden">
                        <img src="{{ $community->cover_url }}" alt="ภาพประกอบ{{ $community->name }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <span class="absolute bottom-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-leaf-800">{{ $community->location_text ?: 'อยุธยา' }}</span>
                    </div>
                    <div class="p-5 sm:p-6">
                        <h3 class="font-display text-xl font-semibold group-hover:text-leaf-700">{{ $community->name }}</h3>
                        <p class="mt-2 text-sm font-medium text-lagoon-700">{{ $community->tagline }}</p>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-muted">{{ $community->description }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-sand pt-4 text-xs text-muted">
                            <span>{{ $community->attractions_count }} จุดแวะ · {{ $community->products_count }} ผลิตภัณฑ์</span>
                            <span class="font-semibold text-leaf-700">รู้จักชุมชน <span aria-hidden="true">→</span></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="container-page pb-16 sm:pb-20">
        <div class="overflow-hidden rounded-[2rem] bg-leaf-900 text-white">
            <div class="grid items-center gap-8 p-7 sm:p-10 lg:grid-cols-[1fr_auto] lg:p-12">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-200">Take the local route</p>
                    <h2 class="mt-3 font-display text-3xl font-semibold text-white sm:text-4xl">วางแผนแวะชุมชน<br>แล้วออกไปพบอยุธยา</h2>
                    <p class="mt-4 max-w-2xl leading-7 text-white/70">ดูแผนที่สถานที่ท่องเที่ยว ค้นหาชุมชน และเลือกเส้นทางที่เหมาะกับเวลาของคุณ</p>
                </div>
                <a href="{{ route('places.index') }}" wire:navigate class="btn-accent justify-self-start px-6 py-3 text-base lg:justify-self-end">เปิดแผนที่ท่องเที่ยว <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="bg-white py-16 sm:py-20">
            <div class="container-page">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">Places with a story</p>
                        <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">ปักหมุดสถานที่น่าแวะ</h2>
                    </div>
                    <a href="{{ route('places.index') }}" wire:navigate class="font-semibold text-leaf-700 hover:underline">ดูทุกสถานที่ →</a>
                </div>
                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featured as $place)
                        @include('partials.place-card', ['place' => $place])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="container-page py-16 sm:py-20">
        <div class="grid gap-8 lg:grid-cols-[.7fr_1.3fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">A little something from the community</p>
                <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">ของฝากที่พาเรื่องราวกลับบ้าน</h2>
            </div>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <p class="max-w-xl leading-7 text-muted">เลือกซื้อผลิตภัณฑ์จากชุมชนได้ตามเดิม รายได้ช่วยสนับสนุนผู้ผลิตท้องถิ่นและงานฝีมือของแต่ละพื้นที่</p>
                <a href="{{ route('shop.index') }}" wire:navigate class="btn-primary">เลือกชมสินค้า <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product, 'canAdd' => false])
            @endforeach
        </div>
    </section>
</div>
