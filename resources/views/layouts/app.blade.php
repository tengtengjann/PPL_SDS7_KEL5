<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'EnergiKita — Edukasi Energi Bersih & Terjangkau (SDG 7)')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Demo Persona Switcher Bar (Memudahkan pengujian & demonstrasi Sprint 1) -->
    <div class="bg-emerald-950 text-emerald-200 text-xs py-1.5 px-4 border-b border-emerald-900/60">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    Sprint 1 • F-01
                </span>
                <span class="hidden sm:inline text-slate-300">Mode Pengguna:</span>
                <span class="font-semibold text-white">
                    @auth
                        {{ Auth::user()->name }} ({{ Auth::user()->role === 'sre' ? 'Admin SRE' : 'Mahasiswa' }})
                    @else
                        Pengunjung (Guest)
                    @endauth
                </span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <span class="text-emerald-400 text-[11px] hidden sm:inline">Simulasi Persona:</span>
                <a href="{{ route('demo.switch', 'mahasiswa') }}" class="px-2 py-0.5 rounded bg-emerald-900/90 hover:bg-emerald-800 text-white transition text-[11px] {{ Auth::check() && Auth::user()->role === 'mahasiswa' ? 'ring-1 ring-emerald-400 font-bold bg-emerald-800' : '' }}">
                    👨‍🎓 Mahasiswa
                </a>
                <a href="{{ route('demo.switch', 'sre') }}" class="px-2 py-0.5 rounded bg-emerald-900/90 hover:bg-emerald-800 text-white transition text-[11px] {{ Auth::check() && Auth::user()->role === 'sre' ? 'ring-1 ring-emerald-400 font-bold bg-emerald-800' : '' }}">
                    🏢 Admin SRE
                </a>
                <a href="{{ route('demo.switch', 'guest') }}" class="px-2 py-0.5 rounded bg-emerald-900/90 hover:bg-emerald-800 text-white transition text-[11px] {{ !Auth::check() ? 'ring-1 ring-emerald-400 font-bold bg-emerald-800' : '' }}">
                    👤 Guest
                </a>
            </div>
        </div>
    </div>

    <!-- ========================================================
         NAVBAR / HEADER: Brand "EnergiKita" (PBI-003)
         Tema Hijau Bersih / Eco-Friendly & Responsif
         ======================================================== -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-emerald-100 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo Brand "EnergiKita" di Kiri -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 via-teal-500 to-amber-400 p-0.5 shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform duration-200">
                            <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                                <!-- Ikon Daun & Cahaya Eco-Friendly SVG -->
                                <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v8"></path>
                                    <path d="m4.93 10.93 1.41 1.41"></path>
                                    <path d="M2 18h2"></path>
                                    <path d="M20 18h2"></path>
                                    <path d="m19.07 10.93-1.41 1.41"></path>
                                    <path d="M22 22H2"></path>
                                    <path d="m8 6 4-4 4 4"></path>
                                    <path d="M16 18a4 4 0 0 0-8 0"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xl font-black tracking-tight text-slate-900">Energi<span class="text-emerald-600">Kita</span></span>
                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                    SDG 7
                                </span>
                            </div>
                            <p class="text-[11px] font-medium text-slate-500 -mt-0.5 hidden sm:block">
                                Edukasi Energi Bersih & Terjangkau
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Menu Navigasi Desktop (PBI-003) -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('home') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('materials.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('materials.*') || request()->routeIs('materi.*') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        Belajar (Materi)
                    </a>
                    <a href="{{ route('quizzes.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('quizzes.*') || request()->routeIs('kuis.*') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        Kuis
                    </a>
                    <a href="{{ route('games.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('games.*') || request()->routeIs('game.*') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        Game
                    </a>
                    <a href="{{ route('leaderboard.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('leaderboard.*') || request()->routeIs('peringkat.*') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        Peringkat
                    </a>
                </nav>

                <!-- Tombol Profil / Login di Kanan (PBI-003) -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <!-- Indikator XP Pengguna Aktif -->
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200/80 text-amber-800 text-xs font-black shadow-xs">
                            <span class="text-sm">⚡</span>
                            <span>{{ Auth::user()->xp }} XP</span>
                        </div>

                        <!-- Tombol Profil -->
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 p-1.5 pr-3 rounded-full hover:bg-emerald-50 transition border border-slate-200">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-slate-800 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                        </a>

                        <!-- Tombol Keluar -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" title="Keluar Akun" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('profile.index') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-xl transition">
                            Profil
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold shadow-md shadow-emerald-600/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>

                <!-- Tombol Hamburger Menu Mobile (NFR-04) -->
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-btn" class="p-2.5 rounded-xl text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 focus:outline-none transition" aria-label="Toggle navigation menu">
                        <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Menu Mobile Drawer (Responsif NFR-04) -->
        <div id="mobile-menu" class="hidden md:hidden border-b border-emerald-100 bg-white px-4 pt-3 pb-6 space-y-2 shadow-xl animate-in slide-in-from-top-2 duration-200">
            <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                Beranda
            </a>
            <a href="{{ route('materials.index') }}" class="block px-3.5 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('materials.*') || request()->routeIs('materi.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                Belajar (Materi)
            </a>
            <a href="{{ route('quizzes.index') }}" class="block px-3.5 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('quizzes.*') || request()->routeIs('kuis.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                Kuis
            </a>
            <a href="{{ route('games.index') }}" class="block px-3.5 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('games.*') || request()->routeIs('game.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                Game
            </a>
            <a href="{{ route('leaderboard.index') }}" class="block px-3.5 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('leaderboard.*') || request()->routeIs('peringkat.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                Peringkat
            </a>
            
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2.5">
                @auth
                    <div class="flex items-center justify-between px-3.5 py-2.5 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-amber-700 font-black">⚡ {{ Auth::user()->xp }} XP</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="text-xs text-emerald-600 font-bold hover:underline">Lihat Profil</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 text-center rounded-xl bg-slate-100 text-rose-600 font-bold text-sm hover:bg-rose-50 transition">
                            Keluar Akun
                        </button>
                    </form>
                @else
                    <a href="{{ route('profile.index') }}" class="w-full py-2.5 px-4 text-center rounded-xl border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition">
                        Profil Pengguna
                    </a>
                    <a href="{{ route('login') }}" class="w-full py-2.5 px-4 text-center rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-md hover:bg-emerald-700 transition">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notification Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold text-lg leading-none">&times;</button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center justify-between p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 font-bold text-lg leading-none">&times;</button>
            </div>
        </div>
    @endif

    <!-- Konten Utama Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ========================================================
         FOOTER SEDERHANA BERTEMA EDUKASI SDG 7
         ======================================================== -->
    <footer class="bg-slate-900 text-slate-300 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                
                <!-- Kolom Brand & Pengenalan SDG 7 -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-base shadow-md shadow-emerald-500/20">
                            EK
                        </div>
                        <div>
                            <span class="text-xl font-extrabold text-white">Energi<span class="text-emerald-400">Kita</span></span>
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-bold uppercase rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/40">
                                SDG 7
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Platform edukasi interaktif untuk mahasiswa dan generasi muda Indonesia dalam memahami, mengadvokasi, dan menerapkan energi bersih, terbarukan, dan terjangkau sesuai pilar SDG 7 PBB.
                    </p>
                    <div class="flex items-center gap-2 pt-2 text-xs text-slate-400">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Berkolaborasi bersama <strong>Organisasi SRE (Society of Renewable Energy)</strong></span>
                    </div>
                </div>

                <!-- Navigasi Belajar -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Navigasi Fitur</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Beranda</a></li>
                        <li><a href="{{ route('materials.index') }}" class="hover:text-emerald-400 transition">Materi Belajar</a></li>
                        <li><a href="{{ route('quizzes.index') }}" class="hover:text-emerald-400 transition">Kuis Cepat</a></li>
                        <li><a href="{{ route('missions.index') }}" class="hover:text-emerald-400 transition">Misi Harian</a></li>
                        <li><a href="{{ route('games.index') }}" class="hover:text-emerald-400 transition">Game Interaktif</a></li>
                        <li><a href="{{ route('leaderboard.index') }}" class="hover:text-emerald-400 transition">Papan Peringkat</a></li>
                    </ul>
                </div>

                <!-- Topik Kategori Energi (PBI-002) -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Kategori Energi</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('materials.category', 'energi-surya') }}" class="hover:text-amber-400 transition">☀️ Energi Surya (Solar)</a></li>
                        <li><a href="{{ route('materials.category', 'energi-angin') }}" class="hover:text-sky-400 transition">💨 Energi Angin (Wind)</a></li>
                        <li><a href="{{ route('materials.category', 'energi-air') }}" class="hover:text-blue-400 transition">💧 Energi Air (Hydro)</a></li>
                        <li><a href="{{ route('materials.category', 'hemat-energi') }}" class="hover:text-emerald-400 transition">⚡ Hemat Energi (Efisiensi)</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} EnergiKita — Edukasi Energi Bersih dan Terjangkau (SDG 7). Sprint 1 oleh Faris Dzakiy.</p>
                <div class="flex items-center gap-4">
                    <span class="hover:text-slate-400 cursor-pointer">Panduan SDG 7</span>
                    <span>&bull;</span>
                    <span class="hover:text-slate-400 cursor-pointer">Society of Renewable Energy</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script Hamburger Menu Mobile Responsif (NFR-04) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            if (btn && menu) {
                btn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                    iconOpen.classList.toggle('hidden');
                    iconClose.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
