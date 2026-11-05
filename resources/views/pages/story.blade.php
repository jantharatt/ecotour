@php($places = \App\Models\Attraction::active()->whereNotNull('faith')->get()->keyBy('faith'))
@php($confluence = \App\Models\Attraction::active()->whereIn('slug', ['wat-phanan-choeng', 'pom-phet'])->get())

<x-layouts.app title="เรื่องราว 3 สายน้ำ 3 ศาสนา">
    <section class="relative isolate overflow-hidden bg-leaf-900 text-white">
        <div class="absolute inset-0 -z-10 opacity-20">
            <div class="absolute -right-24 -top-32 h-96 w-96 rounded-full border border-white/40"></div>
            <div class="absolute -right-10 -top-16 h-64 w-64 rounded-full border border-white/30"></div>
            <div class="absolute -bottom-40 left-1/4 h-80 w-[70rem] -rotate-6 rounded-[50%] border-[3rem] border-lagoon-400/20"></div>
        </div>
        <div class="container-page py-16 sm:py-24">
            <a href="{{ route('home') }}" wire:navigate class="text-sm text-white/65 hover:text-white">← กลับหน้าแรก</a>
            <p class="mt-8 text-sm font-semibold uppercase tracking-[.2em] text-sun-200">A story of rivers, faith & people</p>
            <h1 class="mt-4 max-w-4xl font-display text-4xl font-semibold leading-tight text-white sm:text-6xl">สามสายน้ำ<br><span class="text-sun-200">สามศาสนา</span> และบ้านหลังเดียวกัน</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-white/75">ทำความรู้จักภูมิประเทศที่โอบล้อมเกาะเมือง และชุมชนที่เติบโตท่ามกลางความหลากหลายทางศรัทธา</p>
        </div>
    </section>

    <section class="container-page grid gap-10 py-14 sm:py-20 lg:grid-cols-[.65fr_1.35fr]">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-lagoon-700">01 · The landscape</p>
            <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">เมืองที่สายน้ำ<br>เป็นส่วนหนึ่งของชีวิต</h2>
        </div>
        <div>
            <p class="text-lg leading-8 text-muted">
                อยุธยามีลักษณะเป็น “เกาะเมือง” เพราะแม่น้ำสำคัญสามสายโอบล้อมพื้นที่ไว้
                สายน้ำเป็นทั้งเส้นทางสัญจร แหล่งทำมาหากิน และฉากหลังของชุมชนที่สืบต่อกันมาหลายยุคสมัย
            </p>
            <div class="mt-7 grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['แม่น้ำเจ้าพระยา', 'ไหลผ่านทางทิศตะวันตกและทิศใต้', 'bg-lagoon-500'],
                    ['แม่น้ำป่าสัก', 'ไหลผ่านทางทิศตะวันออก', 'bg-leaf-500'],
                    ['แม่น้ำลพบุรี', 'ไหลผ่านทางทิศเหนือ ปัจจุบันเป็นคลองเมือง', 'bg-sun-500'],
                ] as $i => [$name, $direction, $color])
                    <article class="rounded-2xl border border-sand bg-white p-5">
                        <span class="font-display text-sm font-semibold text-muted/60">0{{ $i + 1 }}</span>
                        <h3 class="mt-5 font-display text-lg font-semibold">{{ $name }}</h3>
                        <p class="mt-2 text-sm leading-6 text-muted">{{ $direction }}</p>
                        <div class="mt-5 h-1 w-12 rounded-full {{ $color }}"></div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6 rounded-3xl bg-[#f0eee4] p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[.16em] text-leaf-700">Where the waterways meet</p>
                <h3 class="mt-2 font-display text-xl font-semibold">มองสายน้ำจากย่านบางกะจะ</h3>
                <p class="mt-3 leading-7 text-muted">บริเวณวัดพนัญเชิงวรวิหารและป้อมเพชรเป็นจุดสำคัญของเรื่องราวการค้าทางน้ำและการเดินทางรอบเกาะเมือง</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach ($confluence as $place)
                        <a href="{{ route('places.show', $place) }}" wire:navigate class="btn-soft btn-sm">{{ $place->type_icon }} {{ $place->name }} <span aria-hidden="true">→</span></a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-leaf-900 py-14 text-white sm:py-20">
        <div class="container-page">
            <div class="grid gap-8 lg:grid-cols-[.65fr_1.35fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-200">02 · A shared home</p>
                    <h2 class="mt-3 font-display text-3xl font-semibold text-white sm:text-4xl">หลากหลายศรัทธา<br>อยู่ร่วมในชุมชนเดียวกัน</h2>
                </div>
                <div>
                    <p class="max-w-3xl text-lg leading-8 text-white/75">พุทธ คริสต์ และอิสลามเป็นส่วนหนึ่งของเรื่องราวผู้คนริมสายน้ำ การเรียนรู้ศาสนสถานจึงเป็นจุดเริ่มต้นของการทำความเข้าใจประวัติชุมชนและการอยู่ร่วมกันอย่างเคารพ</p>
                    <div class="mt-8 space-y-4">
                        @foreach (\App\Models\Attraction::FAITHS as $key => [$label, $icon, $color])
                            @php($place = $places[$key] ?? null)
                            <article class="grid gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 sm:grid-cols-[auto_1fr_auto] sm:items-center sm:p-6">
                                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/10 text-3xl">{{ $icon }}</span>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[.16em]" style="color: {{ $color }}">ศาสนา{{ $label }}</p>
                                    @if ($place)
                                        <h3 class="mt-1 font-display text-xl font-semibold text-white">{{ $place->name }}</h3>
                                        <p class="mt-1 text-sm leading-6 text-white/65">{{ $place->summary }}</p>
                                    @else
                                        <h3 class="mt-1 font-display text-xl font-semibold text-white">เรื่องราวของศาสนา{{ $label }}</h3>
                                        <p class="mt-1 text-sm text-white/65">ข้อมูลสถานที่กำลังรวบรวมจากชุมชน</p>
                                    @endif
                                </div>
                                @if ($place)
                                    <a href="{{ route('places.show', $place) }}" wire:navigate class="text-sm font-semibold text-white hover:text-sun-200">เรียนรู้เพิ่มเติม →</a>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
            <p class="mt-8 border-t border-white/10 pt-5 text-sm text-white/55">เมื่อเข้าเยี่ยมชมศาสนสถาน โปรดแต่งกายสุภาพ เคารพผู้ประกอบศาสนกิจ และทำตามคำแนะนำของแต่ละสถานที่</p>
        </div>
    </section>

    <section class="container-page grid gap-8 py-14 sm:py-20 lg:grid-cols-[.65fr_1.35fr]">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-sun-700">03 · The people</p>
            <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">เรื่องราวยังดำเนินต่อ<br>ในชีวิตประจำวัน</h2>
        </div>
        <div>
            <p class="text-lg leading-8 text-muted">วัฒนธรรมไม่ใช่เพียงสิ่งที่จัดแสดง แต่คือวิถีที่ผู้คนใช้ชีวิต ดูแลสายน้ำ รักษาความเชื่อ และส่งต่อภูมิปัญญา การเดินทางที่ดีจึงเริ่มจากการฟังและให้เกียรติเจ้าของบ้าน</p>
            <div class="mt-7 grid gap-4 sm:grid-cols-2">
                <a href="{{ route('communities.index') }}" wire:navigate class="group rounded-3xl bg-[#f0eee4] p-6 transition hover:bg-leaf-100">
                    <p class="text-3xl" aria-hidden="true">🏡</p>
                    <h3 class="mt-4 font-display text-xl font-semibold">รู้จักชุมชน</h3>
                    <p class="mt-2 text-sm leading-6 text-muted">อ่านที่มา วิถีชีวิต และสิ่งที่แต่ละชุมชนร่วมกันดูแล</p>
                    <span class="mt-5 inline-flex font-semibold text-leaf-700">ไปเยือนอย่างเข้าใจ <span class="ml-2">→</span></span>
                </a>
                <a href="{{ route('shop.index') }}" wire:navigate class="group rounded-3xl bg-sun-50 p-6 transition hover:bg-sun-100">
                    <p class="text-3xl" aria-hidden="true">🧺</p>
                    <h3 class="mt-4 font-display text-xl font-semibold">อุดหนุนของชุมชน</h3>
                    <p class="mt-2 text-sm leading-6 text-muted">เลือกซื้อผลิตภัณฑ์จากผู้ผลิตท้องถิ่นผ่านระบบร้านค้าเดิม</p>
                    <span class="mt-5 inline-flex font-semibold text-sun-700">เลือกชมสินค้า <span class="ml-2">→</span></span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
