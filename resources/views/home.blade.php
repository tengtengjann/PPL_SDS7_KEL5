@extends('layouts.app')

@section('title', 'EnergiKita — Belajar Energi Bersih untuk Masa Depan (SDG 7)')

@section('content')
<div class="space-y-24 sm:space-y-32 pb-8">

    {{-- ========================================================
         1. HERO
         ======================================================== --}}
    <section class="pt-14 sm:pt-20 px-4 sm:px-6 max-w-6xl mx-auto">
        <div class="text-center max-w-4xl mx-auto" data-parallax="-0.06">
            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">
                SDG 7: Energi Bersih dan Terjangkau
            </p>

            {{-- "capitalize" menampilkan setiap kata berhuruf kapital (Untuk) --}}
            <h1 class="mt-5 text-5xl sm:text-6xl lg:text-7xl text-stone-950 leading-[1.04] capitalize">
                Belajar Energi Bersih untuk Masa Depan<span class="text-[#4E6635]">.</span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-stone-500 max-w-2xl mx-auto">
                Kenali energi surya, angin, air, dan cara hemat energi.
            </p>
            <p class="mt-3 text-sm text-stone-400 max-w-xl mx-auto leading-relaxed">
                {{ $platformInfo['sdg7_description'] }}
            </p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('materials.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-[#2D3A1E] hover:bg-[#1F2915] text-white text-sm font-medium transition">
                    Mulai Belajar <span aria-hidden="true">↗</span>
                </a>
                <a href="#kategori-energi"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-[#E6EFE0] hover:bg-[#D5E1CA] text-[#2D3A1E] text-sm font-medium transition">
                    Jelajahi Kategori
                </a>
            </div>
        </div>

        {{-- Mockup layar dengan foto lanskap pegunungan & data XP yang sinkron --}}
        <div class="relative mt-16">
            {{-- Latar belakang sage bergerak dengan kecepatan parallax berbeda untuk kedalaman 3D --}}
            <div class="absolute inset-x-0 bottom-0 top-1/3 bg-[#94A586] rounded-[32px]" data-parallax="0.08"></div>
            <div class="relative mx-auto max-w-4xl px-4 sm:px-0 pb-8 sm:pb-12" data-parallax-scale>
                <div class="rounded-[22px] bg-stone-900 p-2 sm:p-2.5 shadow-2xl shadow-stone-900/20" data-tilt="1.6">
                    <div class="relative rounded-[16px] overflow-hidden aspect-[16/10] sm:aspect-[16/9] text-white">
                        <img src="{{ asset('images/hero-landscape.jpg') }}"
                             alt="Lanskap Pegunungan Energi Bersih SDG 7"
                             data-parallax-img="0.20"
                             class="absolute inset-0 w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-b from-stone-950/55 via-stone-950/25 to-stone-950/75"></div>

                        <div class="relative h-full flex flex-col justify-between p-5 sm:p-9">
                            {{-- Baris Atas: Breadcrumb & Status Pengguna/Badge --}}
                            <div class="flex items-center justify-between gap-2 text-[11px] text-white/80">
                                <span>Ringkasan Capaian &rsaquo; <span class="text-white font-medium">Akumulasi XP</span></span>
                                <span class="px-3 py-1 rounded-full bg-stone-900/45 border border-white/25 backdrop-blur-md text-white font-medium truncate">
                                    {{ $userProgress['is_guest'] ? 'Mode Pengunjung' : $userProgress['name'] }} &middot; {{ $userProgress['badge'] }}
                                </span>
                            </div>

                            {{-- Baris Tengah: Angka Utama XP Terkumpul & Grafik Milestone XP --}}
                            @php
                                $xpValue = (int) ($userProgress['xp'] ?? 0);
                                $communityXp = (int) ($userProgress['total_platform_xp'] ?? $xpValue);
                                $milestones = [25, 50, 100, 150, 200, 250, 300, 450];
                            @endphp
                            <div class="flex items-end justify-between gap-6" data-parallax="-0.035">
                                <div>
                                    <div class="flex flex-wrap items-baseline gap-3 sm:gap-4">
                                        <span class="text-5xl sm:text-7xl font-serif tracking-tight drop-shadow-sm">{{ number_format($xpValue) }} XP</span>
                                        <span class="text-base sm:text-2xl font-serif text-white/95 drop-shadow-sm">Total XP Terkumpul</span>
                                    </div>
                                    <p class="mt-2 text-xs sm:text-sm text-white/80">
                                        @if(!$userProgress['is_guest'])
                                            @if(!empty($userProgress['next_badge']))
                                                {{ $userProgress['xp_needed'] }} XP lagi menuju level <span class="text-white font-medium">{{ $userProgress['next_badge'] }}</span> &middot; Total XP Komunitas: <span class="text-white font-medium">{{ number_format($communityXp) }} XP</span>
                                            @else
                                                Level tertinggi <span class="text-white font-medium">{{ $userProgress['badge'] }}</span> tercapai &middot; Total XP Komunitas: <span class="text-white font-medium">{{ number_format($communityXp) }} XP</span>
                                            @endif
                                        @else
                                            Total XP Komunitas: <span class="text-white font-medium">{{ number_format($communityXp) }} XP</span> &middot; Masuk untuk mulai mengumpulkan XP Anda
                                        @endif
                                    </p>
                                </div>

                                <div class="hidden md:flex items-end gap-3 h-28 shrink-0" aria-hidden="true">
                                    @foreach([30, 45, 55, 65, 72, 82, 90, 100] as $idx => $h)
                                        @php $reached = $xpValue >= ($milestones[$idx] ?? 50); @endphp
                                        <span class="parallax-stem relative w-px {{ $reached ? 'bg-white' : 'bg-white/40' }}" style="height: {{ $h }}%; --stem-delay: {{ $idx * 75 }}ms">
                                            <span class="absolute -top-1 -left-[3px] w-[7px] h-[7px] rounded-[2px] {{ $reached ? 'bg-white shadow-sm shadow-white' : 'bg-white/45' }}"></span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Baris Bawah: 3 Kartu Informasi yang Sinkron dengan XP & Progres --}}
                            <div class="hidden sm:grid grid-cols-3 gap-3 pt-5 border-t border-white/25" data-parallax="-0.02">
                                <div class="px-4 py-3 rounded-2xl border border-white/20 backdrop-blur-md" style="background-color: rgba(20,27,13,.52)">
                                    <p class="text-[10px] uppercase tracking-wider text-white/70">Akumulasi XP Saya</p>
                                    <p class="text-sm font-semibold mt-0.5 text-white">{{ number_format($xpValue) }} XP &middot; {{ $userProgress['completed_count'] }}/{{ $userProgress['total_materials'] }} Modul</p>
                                </div>
                                <div class="px-4 py-3 rounded-2xl border border-white/20 backdrop-blur-md" style="background-color: rgba(20,27,13,.52)">
                                    <p class="text-[10px] uppercase tracking-wider text-white/70">Status Level &amp; Badge</p>
                                    <p class="text-sm font-semibold mt-0.5 text-white truncate">{{ $userProgress['badge'] }} ({{ $userProgress['unlocked_achievements'] ?? 1 }}/{{ $userProgress['total_achievements'] ?? 4 }} Level)</p>
                                </div>
                                <div class="px-4 py-3 rounded-2xl border border-white/20 backdrop-blur-md" style="background-color: rgba(20,27,13,.52)">
                                    <p class="text-[10px] uppercase tracking-wider text-white/70">Total XP Seluruh Pengguna</p>
                                    <p class="text-sm font-semibold mt-0.5 text-white">{{ number_format($communityXp) }} XP Terkumpul</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan XP versi mobile --}}
        <div class="sm:hidden mt-6 grid gap-2 text-sm">
            <p class="text-stone-600"><span class="text-stone-400">Total XP Terkumpul &middot;</span> {{ number_format($xpValue) }} XP ({{ $userProgress['badge'] }})</p>
            <p class="text-stone-600"><span class="text-stone-400">Progres Modul &middot;</span> {{ $userProgress['completed_count'] }}/{{ $userProgress['total_materials'] }} Materi ({{ $userProgress['percentage'] }}%)</p>
            <p class="text-stone-600"><span class="text-stone-400">Total XP Komunitas &middot;</span> {{ number_format($communityXp) }} XP</p>
        </div>
    </section>


    {{-- ========================================================
         2. KATEGORI ENERGI
         ======================================================== --}}
    <section id="kategori-energi" class="max-w-6xl mx-auto px-4 sm:px-6 scroll-mt-24">
        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">Kurikulum</p>
        <h2 class="mt-4 text-4xl sm:text-5xl text-stone-950">Ringkasan kategori energi<span class="text-[#4E6635]">.</span></h2>
        <p class="mt-4 text-stone-500 max-w-lg">Modul pembelajaran yang ringkas dan terarah, tanpa informasi berlebihan.</p>

        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
            @foreach($categories as $category)
                <div class="pt-6 border-t border-stone-200 flex flex-col">
                    <div class="w-5 h-5 text-stone-900">
                        @if($category['icon'] === 'sun')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4" stroke-width="1.6"/><path stroke-linecap="round" stroke-width="1.6" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                        @elseif($category['icon'] === 'wind')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9.59 4.59A2 2 0 1111 8H2m10.59 11.41A2 2 0 1014 16H2m15.73-8.27A2.5 2.5 0 1119.5 12H2"/></svg>
                        @elseif($category['icon'] === 'droplet')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        @endif
                    </div>

                    <div class="mt-5 flex items-baseline justify-between gap-3">
                        <h3 class="text-lg font-serif text-stone-950">{{ $category['name'] }}</h3>
                        <span class="text-[11px] text-stone-400 whitespace-nowrap">{{ $category['material_count'] }} Materi</span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500 leading-relaxed">{{ $category['summary'] }}</p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach($category['highlights'] as $hl)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] bg-stone-100 text-stone-600">{{ $hl }}</span>
                        @endforeach
                    </div>

                    <a href="{{ route('materials.category', $category['slug']) }}"
                       class="mt-auto pt-6 inline-flex items-center gap-1.5 text-xs font-semibold text-[#2D3A1E] hover:underline">
                        Pelajari modul <span aria-hidden="true">↗</span>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Banner lanskap dengan Windowed Image Parallax --}}
        <div class="relative mt-16 h-64 sm:h-80 rounded-[28px] overflow-hidden shadow-sm">
            <img src="{{ asset('images/banner-landscape.jpg') }}"
                 alt="Pemandangan Alam Transisi Energi"
                 data-parallax-img="0.22"
                 class="absolute inset-0 w-full h-full object-cover object-[center_65%]">
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/60 via-stone-950/15 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 sm:p-10 text-white max-w-xl" data-parallax="-0.05">
                <p class="text-[10px] uppercase tracking-[0.2em] text-white/80">Society of Renewable Energy</p>
                <p class="mt-2 text-xl sm:text-2xl font-serif drop-shadow-sm">Bersama menjaga kelestarian bumi dan mendorong bauran energi hijau Indonesia.</p>
            </div>
        </div>
    </section>


    {{-- ========================================================
         3. PILAR SDG 7
         ======================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-stretch">
            <div class="flex flex-col" data-parallax="-0.03">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">Pilar SDG 7</p>
                <h2 class="mt-4 text-4xl sm:text-5xl text-stone-950">Gambaran besarnya<span class="text-[#4E6635]">.</span></h2>
                <p class="mt-4 text-stone-500 max-w-md">Target SDG 7 disajikan dalam panduan yang jelas dan mudah dipahami.</p>

                <ol class="mt-10 border-t border-stone-200 text-sm">
                    @foreach([
                        ['Target 7.1 — Akses Universal', 'Energi terjangkau, andal, dan modern bagi seluruh masyarakat.'],
                        ['Target 7.2 — Energi Terbarukan', 'Meningkatkan porsi pembangkit surya, angin, hidro, dan biomassa.'],
                        ['Target 7.3 — Efisiensi / Hemat Energi', 'Menggandakan laju efisiensi melalui perilaku dan teknologi hemat daya.'],
                        ['Organisasi SRE', 'Kolaborasi mahasiswa memimpin aksi nyata transisi energi.'],
                    ] as $i => [$title, $desc])
                        <li class="py-4 border-b border-stone-200 flex gap-6" data-reveal>
                            <span class="text-stone-400 tabular-nums">0{{ $i + 1 }}</span>
                            <p><span class="text-stone-900 font-medium">{{ $title }}.</span> <span class="text-stone-500">{{ $desc }}</span></p>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-10">
                    <a href="{{ route('materials.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#E6EFE0] hover:bg-[#D5E1CA] text-[#2D3A1E] text-sm font-medium transition">
                        Lihat Semua Materi
                    </a>
                </div>
            </div>

            <div class="bg-[#EDE7DD] rounded-[28px] p-8 sm:p-12 flex flex-col justify-between min-h-[380px] relative overflow-hidden" data-parallax="0.06" data-tilt="1.5">
                <div class="pointer-events-none absolute -top-16 -right-16 w-56 h-56 rounded-full bg-white/40 blur-2xl" data-parallax="-0.12"></div>
                <p class="relative text-[10px] uppercase tracking-[0.2em] text-stone-500">Misi Kolaboratif</p>
                <blockquote class="relative my-10 text-2xl sm:text-3xl font-serif text-stone-900 leading-snug">
                    &ldquo;Transisi energi bukan sekadar mengganti sumber daya, melainkan membangun masa depan yang adil dan lestari.&rdquo;
                </blockquote>
                <div class="relative pt-5 border-t border-stone-300/70 flex items-end justify-between text-xs text-stone-600">
                    <div>
                        <p class="font-semibold text-stone-800">Organisasi SRE</p>
                        <p class="text-stone-500">Society of Renewable Energy</p>
                    </div>
                    <p>{{ $platformInfo['stats']['total_materials'] }}+ modul &middot; Target 2030</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ========================================================
         4. AKSES CEPAT
         ======================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">Fitur Utama</p>
                <h2 class="mt-4 text-4xl sm:text-5xl text-stone-950">Pilih aktivitas belajar<span class="text-[#4E6635]">.</span></h2>
            </div>
            <p class="text-sm text-stone-500 max-w-xs">Materi, kuis, dan misi harian dalam satu tempat.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($quickAccessCards as $i => $card)
                <a href="{{ route($card['route']) }}"
                   data-parallax="{{ number_format(($i - 1) * 0.03, 2) }}"
                   data-tilt="2"
                   class="group rounded-[24px] bg-white border border-stone-200 hover:border-stone-300 p-7 flex flex-col transition">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-serif text-stone-300">0{{ $i + 1 }}</span>
                        <span class="text-[11px] px-3 py-1 rounded-full bg-[#E6EFE0] text-[#2D3A1E]">{{ $card['badge'] }}</span>
                    </div>
                    <h3 class="mt-8 text-xl font-serif text-stone-950">{{ $card['title'] }}</h3>
                    <p class="text-xs text-stone-400 mt-1">{{ $card['subtitle'] }}</p>
                    <p class="mt-3 text-sm text-stone-500 leading-relaxed">{{ $card['description'] }}</p>
                    <span class="mt-auto pt-8 inline-flex items-center gap-1.5 text-xs font-semibold text-[#2D3A1E] group-hover:underline">
                        {{ $card['action_label'] }} <span aria-hidden="true">↗</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>


    {{-- ========================================================
         5. PROGRES & GAMIFIKASI
         ======================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="relative rounded-[28px] bg-[#1F2915] p-8 sm:p-12 text-white overflow-hidden">
            {{-- Dekorasi cahaya ambient bergerak parallax --}}
            <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-[#8EA873]/15 blur-3xl" data-parallax="0.14"></div>
            <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-10">

                <div class="lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span class="px-3 py-1 rounded-full bg-white/10 text-white/80">Ringkasan Progres Belajar</span>
                        <span class="text-white/50">
                            {{ !empty($userProgress['is_guest']) ? 'Mode Pengunjung' : 'Mahasiswa Terdaftar' }}
                        </span>
                    </div>

                    <h2 class="mt-6 text-3xl sm:text-4xl text-white">
                        @if(!empty($userProgress['user']))
                            Semangat Belajar, {{ $userProgress['user']->name }}!
                        @else
                            Mulai perjalanan belajar energi Anda.
                        @endif
                    </h2>

                    <div class="mt-8">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-white/70">Persentase Penyelesaian Materi</span>
                            <span class="font-semibold tabular-nums">{{ $userProgress['percentage'] }}%</span>
                        </div>
                        <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-[#B0C79F] rounded-full" style="width: {{ max(2, $userProgress['percentage']) }}%;"></div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-xs text-white/50">
                            <span>{{ $userProgress['completed_count'] }} dari {{ $userProgress['total_materials'] }} materi telah diselesaikan</span>
                            @auth
                                <a href="{{ route('learning-progress.index') }}" class="text-white/80 hover:text-white">Detail &rarr;</a>
                            @endauth
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-px bg-white/10 rounded-2xl overflow-hidden">
                        <div class="bg-[#1F2915] p-5">
                            <p class="text-[10px] uppercase tracking-wider text-white/50">Total Akumulasi</p>
                            <p class="mt-1 text-2xl font-serif">XP: {{ $userProgress['xp'] }}</p>
                        </div>
                        <div class="bg-[#1F2915] p-5">
                            <p class="text-[10px] uppercase tracking-wider text-white/50">Gelar Pencapaian</p>
                            <p class="mt-1 text-2xl font-serif truncate">Badge: {{ $userProgress['badge'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 lg:pl-10 lg:border-l border-white/10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold">Misi Harian Tersedia</h3>
                        <a href="{{ route('missions.index') }}" class="text-xs text-white/60 hover:text-white">Semua &rarr;</a>
                    </div>
                    <div class="mt-4 divide-y divide-white/10">
                        @foreach($dailyMissions as $mission)
                            <div class="py-4 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm text-white truncate">{{ $mission->title }}</p>
                                    <p class="text-xs text-white/50 mt-0.5">{{ $mission->criteria }}</p>
                                </div>
                                <span class="text-xs text-[#CFDEC4] tabular-nums shrink-0">+{{ $mission->xp }} XP</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('materials.index') }}"
                       class="mt-6 block w-full py-3 rounded-full bg-[#E6EFE0] hover:bg-white text-[#2D3A1E] text-sm font-medium text-center transition">
                        Lanjutkan Belajar ↗
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
