@extends('layouts.app')

@section('title', 'EnergiKita — Belajar Energi Bersih untuk Masa Depan (SDG 7)')

@section('content')
<div class="space-y-16 sm:space-y-24 pb-16">

    <!-- ========================================================
         1. HERO SECTION & RINGKASAN SDG 7 (PBI-001)
         - Judul: "Belajar Energi Bersih untuk Masa Depan"
         - Subjudul: "Kenali energi surya, angin, air, dan cara hemat energi"
         - Ringkasan SDG 7 (Affordable and Clean Energy)
         ======================================================== -->
    <section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-teal-900 to-slate-900 text-white pt-16 pb-20 lg:pt-20 lg:pb-28">
        <!-- Background Decorative Glow & Elements (NFR-04) -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-32 w-80 h-80 bg-amber-400 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 right-1/4 w-72 h-72 bg-blue-500 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Kolom Teks Hero (PBI-001) -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Mendukung Tujuan Pembangunan Berkelanjutan (SDG 7)</span>
                    </div>

                    <!-- Judul Hero (Sesuai Proposal) -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        {{ $platformInfo['tagline'] }}
                    </h1>

                    <!-- Subjudul Hero (Sesuai Proposal) -->
                    <p class="text-lg sm:text-xl text-emerald-100/90 font-medium leading-relaxed">
                        {{ $platformInfo['subtitle'] }}
                    </p>

                    <!-- Penjelasan Pengenalan EnergiKita -->
                    <p class="text-sm sm:text-base text-slate-300 max-w-2xl leading-relaxed">
                        {{ $platformInfo['sdg7_description'] }} Bergabunglah bersama ribuan mahasiswa dan generasi muda Indonesia dalam mempercepat transisi energi menuju sumber daya terbarukan yang ramah lingkungan, mandiri, dan berkeadilan.
                    </p>

                    <!-- Tombol Aksi Hero (PBI-003) -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                        <a href="{{ route('materials.index') }}" 
                           class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-sm sm:text-base shadow-lg shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Mulai Belajar Sekarang</span>
                        </a>

                        <a href="#kategori-energi" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold text-sm sm:text-base backdrop-blur-md transition-all">
                            <span>Jelajahi Kategori</span>
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </div>

                    <!-- Statistik Mini Platform -->
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-emerald-800/60 max-w-lg mx-auto lg:mx-0">
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-amber-400">{{ $platformInfo['stats']['total_materials'] }}+</div>
                            <div class="text-xs text-slate-400 font-medium">Modul Pembelajaran</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">100%</div>
                            <div class="text-xs text-slate-400 font-medium">Akses Edukasi Terbuka</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-sky-400">2030</div>
                            <div class="text-xs text-slate-400 font-medium">Target Capaian SDG 7</div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kartu / Blok Ringkasan SDG 7 (PBI-001) -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-3xl bg-gradient-to-br from-white/10 to-white/5 border border-white/15 p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-6">
                        
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 font-black text-xl flex items-center justify-center shadow-lg shadow-amber-400/30">
                                    7
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-white leading-tight">{{ $platformInfo['sdg7_title'] }}</h2>
                                    <p class="text-xs text-amber-300 font-medium">Affordable and Clean Energy</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                                PBB 2030
                            </span>
                        </div>

                        <!-- 3 Pilar Sasaran Utama SDG 7 -->
                        <div class="space-y-3.5">
                            @foreach($platformInfo['sdg7_pillars'] as $pillar)
                                <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 font-bold text-sm">
                                        @if($pillar['target'] === 'Target 7.1')
                                            🔌
                                        @elseif($pillar['target'] === 'Target 7.2')
                                            ⚡
                                        @else
                                            💡
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">{{ $pillar['target'] }}</span>
                                            <span class="text-xs text-slate-400">&bull;</span>
                                            <span class="text-sm font-semibold text-white">{{ $pillar['title'] }}</span>
                                        </div>
                                        <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">{{ $pillar['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kolaborasi Mitra Organisasi SRE -->
                        <div class="pt-2 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
                            <span>Didukung oleh:</span>
                            <span class="text-white font-semibold">{{ $platformInfo['stats']['partner'] }}</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================
         2. SECTION RINGKASAN KATEGORI ENERGI (PBI-002)
         (Sesuai susunan proposal: tepat setelah Hero Section)
         - Energi Surya, Energi Angin, Energi Air, Hemat Energi
         - Nama, visual ikon, ringkasan relevan, dan jumlah materi
         ======================================================== -->
    <section id="kategori-energi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
        
        <!-- Header Section -->
        <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                ⚡ Eksplorasi Topik EBT
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Ringkasan Kategori Energi Bersih
            </h2>
            <p class="text-base text-slate-600 leading-relaxed">
                Pelajari pilar-pilar utama transisi energi bersih di Indonesia, mulai dari potensi alam hingga budaya hemat energi sehari-hari.
            </p>
        </div>

        <!-- Grid Kartu Kategori Energi (PBI-002: Desktop & Mobile Responsive) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <div class="group relative rounded-3xl bg-white p-6 shadow-sm hover:shadow-xl border border-slate-200 hover:border-emerald-300 transition-all duration-300 flex flex-col justify-between">
                    
                    <div>
                        <!-- Header Ikon Visual Kategori & Counter Materi -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-xs transition-transform group-hover:scale-110 duration-200
                                {{ $category['color'] === 'amber' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $category['color'] === 'sky' ? 'bg-sky-100 text-sky-700' : '' }}
                                {{ $category['color'] === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $category['color'] === 'emerald' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                @if($category['icon'] === 'sun')
                                    ☀️
                                @elseif($category['icon'] === 'wind')
                                    💨
                                @elseif($category['icon'] === 'droplet')
                                    💧
                                @else
                                    ⚡
                                @endif
                            </div>

                            <!-- Jumlah Materi Aktif dari Database (PBI-002) -->
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-slate-200 transition">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>{{ $category['material_count'] }} Materi</span>
                            </span>
                        </div>

                        <!-- Tag & Nama Kategori -->
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            {{ $category['tag'] }}
                        </span>
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                            {{ $category['name'] }}
                        </h3>

                        <!-- Ringkasan Relevan Kategori -->
                        <p class="text-sm text-slate-600 mt-2.5 leading-relaxed line-clamp-3">
                            {{ $category['summary'] }}
                        </p>

                        <!-- Highlights Topik Materi -->
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Topik Kunci:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($category['highlights'] as $hl)
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200/70">
                                        {{ $hl }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Menuju Materi Kategori Tersebut (PBI-002) -->
                    <div class="pt-5 mt-5">
                        <a href="{{ route('materials.category', $category['slug']) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs font-bold border border-slate-200 text-slate-700 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 transition">
                            <span>Pelajari Kategori Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </section>


    <!-- ========================================================
         3. SECTION 3 KARTU NAVIGASI CEPAT (PBI-003)
         - "Materi Energi (Pelajari topik)"
         - "Kuis Cepat (Uji pemahaman)"
         - "Misi Harian (Kumpulkan XP)"
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider">
                🚀 Akses Langsung Fitur Utama
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Pilih Aktivitas Belajar Anda
            </h2>
            <p class="text-sm text-slate-600">
                Navigasi cepat menuju fitur pembelajaran, evaluasi pemahaman energi, dan misi gamifikasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($quickAccessCards as $card)
                <div class="group relative rounded-3xl bg-white p-7 shadow-lg shadow-slate-200/70 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 flex flex-col justify-between">
                    
                    <!-- Decorative Top Color Strip -->
                    <div class="absolute top-0 left-8 right-8 h-1 rounded-b-full 
                        {{ $card['color'] === 'emerald' ? 'bg-emerald-500' : '' }}
                        {{ $card['color'] === 'blue' ? 'bg-blue-500' : '' }}
                        {{ $card['color'] === 'amber' ? 'bg-amber-500' : '' }}">
                    </div>

                    <div>
                        <!-- Header Kartu: Ikon & Badge -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm
                                {{ $card['color'] === 'emerald' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                                {{ $card['color'] === 'blue' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                {{ $card['color'] === 'amber' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}">
                                @if($card['id'] === 'materi')
                                    📚
                                @elseif($card['id'] === 'kuis')
                                    🎯
                                @else
                                    🚀
                                @endif
                            </div>
                            <span class="text-xs font-extrabold px-3 py-1 rounded-full 
                                {{ $card['color'] === 'emerald' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $card['color'] === 'blue' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $card['color'] === 'amber' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}">
                                {{ $card['badge'] }}
                            </span>
                        </div>

                        <!-- Judul & Subjudul Kartu (Sesuai Proposal) -->
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                            {{ $card['title'] }} <span class="text-sm font-semibold text-slate-500">({{ $card['subtitle'] }})</span>
                        </h3>

                        <!-- Deskripsi Kartu -->
                        <p class="text-sm text-slate-600 mt-2.5 leading-relaxed">
                            {{ $card['description'] }}
                        </p>
                    </div>

                    <!-- Tombol Navigasi Aksi (PBI-003) -->
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <a href="{{ route($card['route']) }}" 
                           class="w-full inline-flex items-center justify-between px-4 py-3 rounded-xl font-bold text-sm transition-all
                           {{ $card['color'] === 'emerald' ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/20' : '' }}
                           {{ $card['color'] === 'blue' ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20' : '' }}
                           {{ $card['color'] === 'amber' ? 'bg-amber-500 hover:bg-amber-600 text-slate-950 shadow-md shadow-amber-500/20' : '' }}">
                            <span>{{ $card['action_label'] }}</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </section>


    <!-- ========================================================
         4. SECTION PROGRES BELAJAR (Di Bagian Bawah Halaman)
         - Progress bar visual persentase materi
         - Indikator "XP: [jumlah]" (misal: "XP: 120" atau dinamis)
         - Indikator "Badge: [nama badge, misal: Energy Beginner]"
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 p-6 sm:p-10 text-white shadow-2xl border border-slate-700/60 relative overflow-hidden">
            
            <!-- Background Glow Decorator -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Kolom Status & Indikator Progres Belajar Siswa -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Ringkasan Progres Belajar</span>
                        </span>

                        @if(!empty($userProgress['is_guest']) && $userProgress['is_guest'])
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-500/20 text-slate-300 border border-slate-400/30 font-medium">
                                Mode Pengunjung (Guest)
                            </span>
                        @else
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 font-medium">
                                Mahasiswa Terdaftar
                            </span>
                        @endif
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                            @if(!empty($userProgress['user']))
                                Semangat Belajar, {{ $userProgress['user']->name }}! 👋
                            @else
                                Mulai Perjalanan Belajar Energi Anda!
                            @endif
                        </h2>
                        <p class="text-sm sm:text-base text-slate-300 mt-2">
                            Pantau persentase materi yang telah Anda selesaikan, perolehan XP, dan tingkatkan badge Anda untuk membuka prestasi baru.
                        </p>
                    </div>

                    <!-- Progress Bar Visual Persentase Penyelesaian Materi -->
                    <div class="space-y-2.5 bg-white/5 p-4 sm:p-5 rounded-2xl border border-white/10 backdrop-blur-sm">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-slate-200">Persentase Penyelesaian Materi</span>
                            <span class="font-bold text-emerald-400 text-base">{{ $userProgress['percentage'] }}%</span>
                        </div>

                        <!-- Bar Visual -->
                        <div class="w-full h-3.5 bg-slate-700/80 rounded-full overflow-hidden p-0.5 border border-white/10">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-amber-400 rounded-full transition-all duration-500 shadow-sm"
                                 style="width: {{ max(4, $userProgress['percentage']) }}%;">
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
                            <span>{{ $userProgress['completed_count'] }} dari {{ $userProgress['total_materials'] }} materi telah diselesaikan</span>
                            <span>{{ $userProgress['in_progress_count'] }} sedang dipelajari</span>
                        </div>
                    </div>

                    <!-- Indikator XP dan Badge (Sesuai Mockup Proposal) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Indikator "XP: [jumlah]" -->
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl shrink-0 font-bold">
                                ⚡
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 font-medium block">Total Akumulasi</span>
                                <div class="text-xl font-black text-amber-400">XP: {{ $userProgress['xp'] }}</div>
                                <span class="text-[11px] text-slate-400">Poin pengalaman belajar</span>
                            </div>
                        </div>

                        <!-- Indikator "Badge: [nama badge, misal Energy Beginner]" -->
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shrink-0 font-bold">
                                🏅
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 font-medium block">Gelar Pencapaian</span>
                                <div class="text-xl font-black text-emerald-400">Badge: {{ $userProgress['badge'] }}</div>
                                @if(!empty($userProgress['next_badge']))
                                    <span class="text-[11px] text-slate-300">Target: {{ $userProgress['next_badge'] }} ({{ $userProgress['xp_needed'] }} XP lagi)</span>
                                @else
                                    <span class="text-[11px] text-emerald-300">Tingkat Tertinggi Terbuka!</span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Kolom Misi Harian Singkat & Quick Action -->
                <div class="lg:col-span-5 bg-white/5 rounded-3xl p-6 border border-white/10 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🎯</span>
                            <h3 class="text-base font-bold text-white">Misi Harian Tersedia</h3>
                        </div>
                        <a href="{{ route('missions.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                            Semua Misi &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($dailyMissions as $mission)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition">
                                <div class="space-y-0.5 pr-2">
                                    <p class="text-xs font-semibold text-slate-200">{{ $mission->title }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $mission->criteria }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-lg bg-amber-400/20 text-amber-300 text-xs font-bold shrink-0 border border-amber-400/30">
                                    +{{ $mission->xp }} XP
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('materials.index') }}" 
                           class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm text-center block transition shadow-lg shadow-emerald-900/30">
                            Lanjutkan Belajar Sekarang
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>
@endsection
