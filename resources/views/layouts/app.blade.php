<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'EnergiKita — Edukasi Energi Bersih & Terjangkau (SDG 7)')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN + tema global "Area" (eco-minimalism) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Palet tunggal: moss (hijau lumut) & sand (pasir hangat).
        // Warna bawaan Tailwind yang mencolok (blue, emerald, amber, indigo, purple, dll.)
        // dipetakan ulang ke palet ini agar SEMUA halaman otomatis selaras.
        const moss = { 50:'#F4F7F1',100:'#E6EFE0',200:'#CFDEC4',300:'#B0C79F',400:'#8EA873',500:'#6B8752',600:'#4E6635',700:'#3D5229',800:'#2D3A1E',900:'#1F2915',950:'#141B0D', DEFAULT:'#2D3A1E', hover:'#1F2915', light:'#E6EFE0', tint:'#F4F7F1' };
        const sand = { 50:'#FAF7F2',100:'#F3EDE3',200:'#EDE7DD',300:'#DCD4C7',400:'#C4B59D',500:'#A8916E',600:'#8A7352',700:'#6E5A40',800:'#54452F',900:'#3B3021',950:'#241D14', DEFAULT:'#EDE7DD', light:'#F8F6F2', dark:'#DCD4C7' };
        const stone = { 50:'#FAFAF9',100:'#F5F5F4',200:'#E7E5E4',300:'#D6D3D1',400:'#A8A29E',500:'#78716C',600:'#57534E',700:'#44403C',800:'#292524',900:'#1C1917',950:'#0C0A09' };
        const clay = { 50:'#FBF4F2',100:'#F5E4DF',200:'#EBC9BF',300:'#DCA595',400:'#C97D69',500:'#B4604B',600:'#9A4B39',700:'#7D3C2E',800:'#5F2E24',900:'#43211A',950:'#2A140F' };
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Newsreader', 'Georgia', 'serif'],
                        sans: ['Plus Jakarta Sans', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        moss, sand,
                        slate: stone, gray: stone, zinc: stone, neutral: stone,
                        emerald: moss, green: moss, lime: moss, teal: moss,
                        blue: moss, sky: moss, cyan: moss, indigo: moss,
                        purple: moss, violet: moss,
                        amber: sand, yellow: sand, orange: sand,
                        red: clay, rose: clay,
                    }
                }
            }
        }
    </script>

    <style>
        html, body { overflow-x: hidden; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #FAF9F5;
            color: #1A1F16;
        }
        /* Tipografi editorial: semua judul besar memakai serif ringan */
        h1, h2, .font-editorial {
            font-family: 'Newsreader', Georgia, serif;
            font-weight: 400 !important;
            letter-spacing: -0.02em;
        }
        h3 { letter-spacing: -0.01em; }
        .font-extrabold, .font-black { font-weight: 600 !important; }
        /* Semua tombol/link berbentuk pill (konsisten dengan art style) */
        a.rounded-xl, button.rounded-xl, a.rounded-lg, button.rounded-lg { border-radius: 9999px; }
        /* Hilangkan bayangan berwarna yang berlebihan */
        [class*="shadow-blue"], [class*="shadow-emerald"], [class*="shadow-amber"] { --tw-shadow-color: rgba(45,58,30,.08) !important; }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        [x-cloak] { display: none !important; }

        /* ========================================================
           SMOOTH PARALLAX & SCROLL REVEAL ENGINE ("Area" Motion)
           ======================================================== */
        [data-parallax],
        [data-parallax-img],
        [data-parallax-scale],
        [data-tilt] {
            will-change: transform;
            backface-visibility: hidden;
        }
        [data-parallax-img] {
            transform: translate3d(0, 0, 0) scale(1.18);
            transition: filter 0.6s ease;
        }
        .reveal-init {
            opacity: 0;
            transform: translate3d(0, 26px, 0);
            transition:
                opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: var(--reveal-delay, 0ms);
            will-change: opacity, transform;
        }
        .reveal-init.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .parallax-stem {
            transform-origin: bottom center;
            transform: scaleY(0.15);
            transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: var(--stem-delay, 0ms);
        }
        .parallax-stem.is-visible {
            transform: scaleY(1);
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal-init {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            [data-parallax], [data-parallax-img], [data-parallax-scale] {
                transform: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#FAF9F5] text-stone-900 antialiased flex flex-col min-h-screen">

    @php
        $navLinks = [
            ['route' => 'home', 'label' => 'Beranda', 'active' => ['home']],
            ['route' => 'materials.index', 'label' => 'Materi', 'active' => ['materials.*', 'materi.*']],
            ['route' => 'quizzes.index', 'label' => 'Kuis', 'active' => ['quizzes.*', 'kuis.*', 'quiz.*']],
            ['route' => 'missions.index', 'label' => 'Misi', 'active' => ['missions.*', 'misi.*']],
            ['route' => 'games.index', 'label' => 'Game', 'active' => ['games.*', 'game.*']],
            ['route' => 'leaderboard.index', 'label' => 'Peringkat', 'active' => ['leaderboard.*', 'peringkat.*']],
        ];
        $isSre = Auth::check() && Auth::user()->isSreAdmin();
    @endphp

    {{-- Bar simulasi persona (demo) — ringkas, satu baris, tidak melebar --}}
    <div class="bg-[#1C2415] text-stone-400 text-[11px]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-9 flex items-center justify-between gap-3">
            <p class="truncate min-w-0">
                <span class="hidden sm:inline">Mode demo &middot; </span>
                <span class="text-stone-200 font-medium">
                    @auth
                        {{ Auth::user()->isSreAdmin() ? 'Admin SRE' : 'Mahasiswa' }}
                    @else
                        Pengunjung
                    @endauth
                </span>
            </p>
            <div class="flex items-center gap-0.5 p-0.5 rounded-full bg-white/5 shrink-0">
                @php
                    $personas = [
                        'mahasiswa' => ['Mahasiswa', Auth::check() && Auth::user()->role === 'mahasiswa'],
                        'sre' => ['Admin SRE', Auth::check() && Auth::user()->isSreAdmin()],
                        'guest' => ['Guest', !Auth::check()],
                    ];
                @endphp
                @foreach($personas as $key => [$label, $active])
                    <a href="{{ route('demo.switch', $key) }}"
                       class="px-2.5 py-0.5 rounded-full transition {{ $active ? 'bg-[#E6EFE0] text-[#2D3A1E] font-semibold' : 'text-stone-400 hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 bg-[#FAF9F5]/90 backdrop-blur-md border-b border-stone-200/70">
        {{-- Scroll Progress Bar Halus di Puncak Navbar --}}
        <div class="h-[2px] w-full bg-transparent overflow-hidden">
            <div id="scroll-progress-bar" class="h-full bg-[#4E6635] origin-left scale-x-0 transition-transform duration-75 ease-out"></div>
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16 gap-6">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="shrink-0 text-2xl font-serif tracking-tight text-stone-950 hover:text-[#2D3A1E] transition">
                    EnergiKita<span class="text-[#4E6635]">.</span>
                </a>

                {{-- Navigasi utama (desktop) --}}
                <nav class="hidden lg:flex items-center gap-7 text-[13px] font-medium whitespace-nowrap">
                    @foreach($navLinks as $link)
                        @php $active = request()->routeIs(...$link['active']); @endphp
                        <a href="{{ route($link['route']) }}"
                           class="relative py-1 transition {{ $active ? 'text-stone-950' : 'text-stone-500 hover:text-stone-950' }}">
                            {{ $link['label'] }}
                            @if($active)
                                <span class="absolute left-0 right-0 -bottom-0.5 h-px bg-stone-900"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                {{-- Aksi kanan --}}
                <div class="flex items-center gap-2 shrink-0">
                    @auth
                        @if($isSre)
                            <a href="{{ route('admin.materials.index') }}"
                               class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.*') ? 'bg-[#2D3A1E] text-white' : 'bg-[#E6EFE0] text-[#2D3A1E] hover:bg-[#D9E6D2]' }}">
                                Panel SRE <span aria-hidden="true">↗</span>
                            </a>
                        @endif

                        {{-- Dropdown profil --}}
                        <details class="relative hidden sm:block" id="profile-menu">
                            <summary class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-stone-200 hover:border-stone-300 bg-white/60 cursor-pointer select-none transition">
                                <span class="w-7 h-7 rounded-full bg-[#2D3A1E] text-white text-[11px] font-semibold flex items-center justify-center">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="text-xs font-semibold text-stone-800 tabular-nums">{{ Auth::user()->xp }} XP</span>
                                <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </summary>
                            <div class="absolute right-0 mt-2 w-64 rounded-2xl bg-white border border-stone-200 shadow-xl shadow-stone-900/5 p-2 text-sm">
                                <div class="px-3 py-3 border-b border-stone-100 mb-1">
                                    <p class="font-semibold text-stone-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-stone-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Profil Saya</a>
                                <a href="{{ route('learning-progress.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Progres Belajar</a>
                                <a href="{{ route('quiz-history.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Riwayat Kuis</a>
                                <a href="{{ route('badges.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Badge Pencapaian</a>
                                @if($isSre)
                                    <div class="my-1 border-t border-stone-100"></div>
                                    <p class="px-3 pt-2 pb-1 text-[10px] uppercase tracking-wider text-stone-400 font-semibold">Panel SRE</p>
                                    <a href="{{ route('admin.materials.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Kelola Materi &amp; Kuis</a>
                                    <a href="{{ route('admin.badges.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Kelola Badge</a>
                                @endif
                                <div class="my-1 border-t border-stone-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-[#9A4B39] hover:bg-[#FBF4F2]">Keluar</button>
                                </form>
                            </div>
                        </details>
                    @else
                        <a href="{{ route('profile.index') }}" class="hidden sm:inline text-xs font-medium text-stone-500 hover:text-stone-900 px-2 transition">Profil</a>
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-[#2D3A1E] hover:bg-[#1F2915] text-white text-xs font-medium transition">
                            Masuk <span aria-hidden="true">↗</span>
                        </a>
                    @endauth

                    {{-- Hamburger --}}
                    <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 -mr-2 rounded-full text-stone-700 hover:bg-stone-200/50 transition" aria-label="Buka menu">
                        <svg id="menu-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        <svg id="menu-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div id="mobile-menu" class="hidden lg:hidden border-t border-stone-200 bg-[#FAF9F5]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 grid gap-1 text-sm">
                <a href="{{ route('home') }}" class="py-2 {{ request()->routeIs('home') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Beranda</a>
                <a href="{{ route('materials.index') }}" class="py-2 {{ request()->routeIs('materials.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Materi Belajar</a>
                <a href="{{ route('quizzes.index') }}" class="py-2 {{ request()->routeIs('quizzes.*', 'quiz.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Kuis Cepat</a>
                <a href="{{ route('missions.index') }}" class="py-2 {{ request()->routeIs('missions.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Misi Harian</a>
                <a href="{{ route('games.index') }}" class="py-2 {{ request()->routeIs('games.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Game Edukasi</a>
                <a href="{{ route('leaderboard.index') }}" class="py-2 {{ request()->routeIs('leaderboard.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Papan Peringkat</a>
                <a href="{{ route('badges.index') }}" class="py-2 {{ request()->routeIs('badges.*') ? 'font-semibold text-stone-950' : 'text-stone-600' }}">Badge Pencapaian</a>
                @auth
                    <a href="{{ route('learning-progress.index') }}" class="py-2 text-stone-600">Progres Belajar</a>
                    <a href="{{ route('quiz-history.index') }}" class="py-2 text-stone-600">Riwayat Kuis</a>
                    <a href="{{ route('profile.index') }}" class="py-2 text-stone-600">Profil Saya</a>
                    @if($isSre)
                        <div class="mt-2 pt-3 border-t border-stone-200 grid gap-1">
                            <a href="{{ route('admin.materials.index') }}" class="py-2 font-semibold text-[#2D3A1E]">Panel SRE — Materi &amp; Kuis</a>
                            <a href="{{ route('admin.badges.index') }}" class="py-2 font-semibold text-[#2D3A1E]">Panel SRE — Badge</a>
                        </div>
                    @endif
                    <div class="mt-2 pt-3 border-t border-stone-200 flex items-center justify-between">
                        <span class="text-stone-800 font-medium truncate">{{ Auth::user()->name }} &middot; {{ Auth::user()->xp }} XP</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-1.5 rounded-full bg-stone-100 text-[#9A4B39] text-xs font-medium">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="mt-3 block py-2.5 text-center rounded-full bg-[#2D3A1E] text-white text-xs font-medium">Masuk ke Akun ↗</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Notifikasi flash --}}
    @foreach(['success' => 'bg-[#E6EFE0] border-[#CFDEC4] text-[#243118]', 'error' => 'bg-[#FBF4F2] border-[#EBC9BF] text-[#7D3C2E]', 'info' => 'bg-stone-100 border-stone-200 text-stone-800'] as $type => $style)
        @if(session($type))
            <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 mt-4">
                <div class="flex items-center justify-between gap-4 px-5 py-3.5 rounded-2xl border text-sm {{ $style }}">
                    <span class="font-medium">{{ session($type) }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100 text-lg leading-none" aria-label="Tutup">&times;</button>
                </div>
            </div>
        @endif
    @endforeach

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-stone-200 mt-24 overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-20 pb-16 text-center space-y-6" data-parallax="-0.05">
            <h2 class="text-4xl sm:text-5xl text-stone-950" data-reveal>Mewujudkan masa depan energi bersih.</h2>
            <p class="text-stone-500 text-base max-w-xl mx-auto leading-relaxed" data-reveal>
                Platform edukasi interaktif bagi mahasiswa Indonesia untuk memahami dan menerapkan energi baru terbarukan (SDG 7).
            </p>
            <div data-reveal>
                <a href="{{ route('materials.index') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#2D3A1E] hover:bg-[#1F2915] text-white text-sm font-medium transition">
                    Mulai Belajar <span aria-hidden="true">↗</span>
                </a>
            </div>
            <p class="text-xs text-stone-400" data-reveal>Berkolaborasi bersama Organisasi SRE (Society of Renewable Energy)</p>
        </div>

        <div class="border-t border-stone-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6 text-xs text-stone-500">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <a href="{{ route('home') }}" class="font-serif text-base text-stone-900">EnergiKita.</a>
                    <a href="{{ route('materials.index') }}" class="hover:text-stone-900 transition">Materi Belajar</a>
                    <a href="{{ route('quizzes.index') }}" class="hover:text-stone-900 transition">Kuis Cepat</a>
                    <a href="{{ route('missions.index') }}" class="hover:text-stone-900 transition">Misi Harian</a>
                    <a href="{{ route('leaderboard.index') }}" class="hover:text-stone-900 transition">Peringkat</a>
                    <a href="{{ route('profile.index') }}" class="hover:text-stone-900 transition">Profil</a>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('materials.category', 'energi-surya') }}" class="px-3.5 py-1.5 rounded-full bg-stone-100 text-stone-700 hover:bg-stone-200 transition">Surya</a>
                    <a href="{{ route('materials.category', 'energi-angin') }}" class="px-3.5 py-1.5 rounded-full bg-stone-100 text-stone-700 hover:bg-stone-200 transition">Angin</a>
                    <a href="{{ route('materials.category', 'energi-air') }}" class="px-3.5 py-1.5 rounded-full bg-stone-100 text-stone-700 hover:bg-stone-200 transition">Air</a>
                    <a href="{{ route('materials.category', 'hemat-energi') }}" class="px-3.5 py-1.5 rounded-full bg-[#E6EFE0] text-[#2D3A1E] hover:bg-[#D5E1CA] transition">Hemat Energi</a>
                </div>
            </div>
            <div class="border-t border-stone-100">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-stone-400">
                    <p>&copy; {{ date('Y') }} EnergiKita — Edukasi Energi Bersih dan Terjangkau (SDG 7).</p>
                    <p>Target 7.1 &middot; 7.2 &middot; 7.3</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                    document.getElementById('menu-icon-open').classList.toggle('hidden');
                    document.getElementById('menu-icon-close').classList.toggle('hidden');
                });
            }
            // Tutup dropdown profil saat klik di luar
            const profile = document.getElementById('profile-menu');
            if (profile) {
                document.addEventListener('click', function (e) {
                    if (!profile.contains(e.target)) profile.removeAttribute('open');
                });
            }

            // ============================================================
            // 1. AUTO STAGGERED SCROLL-REVEAL (Seluruh Halaman & Fitur)
            // ============================================================
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!prefersReducedMotion && 'IntersectionObserver' in window) {
                // Daftarkan elemen otomatis di semua halaman jika belum diberi data-reveal
                const autoSelectors = [
                    'main h1',
                    'main h2',
                    'main section > div',
                    'main .grid > a',
                    'main .grid > div',
                    'main .divide-y > div',
                    'main table tbody tr',
                    '[data-reveal]'
                ];
                const candidates = document.querySelectorAll(autoSelectors.join(','));
                const parentCounters = new Map();

                candidates.forEach(function (el) {
                    // Jangan sembunyikan kontainer besar yang membungkus seluruh halaman
                    if (el.querySelector('section') || el.id === 'materials-container') return;
                    if (el.classList.contains('reveal-init')) return;

                    const parent = el.parentElement;
                    const count = parentCounters.get(parent) || 0;
                    parentCounters.set(parent, count + 1);

                    const delay = Math.min(count * 65, 320);
                    el.style.setProperty('--reveal-delay', delay + 'ms');
                    el.classList.add('reveal-init');
                });

                const revealObserver = new IntersectionObserver(function (entries, obs) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

                document.querySelectorAll('.reveal-init, .parallax-stem').forEach(function (el) {
                    revealObserver.observe(el);
                });
            }

            // ============================================================
            // 2. SMOOTH LERP PARALLAX ENGINE (60fps requestAnimationFrame)
            // ============================================================
            if (!prefersReducedMotion) {
                const parallaxEls = Array.from(document.querySelectorAll('[data-parallax]'));
                const parallaxImgs = Array.from(document.querySelectorAll('[data-parallax-img]'));
                const parallaxScales = Array.from(document.querySelectorAll('[data-parallax-scale]'));
                const progressBar = document.getElementById('scroll-progress-bar');

                let currentScroll = window.scrollY || 0;
                let targetScroll = currentScroll;
                let rafId = null;

                function clamp(val, min, max) {
                    return Math.max(min, Math.min(max, val));
                }

                function updateParallax() {
                    // Linear interpolation (lerp) untuk efek inersia yang sangat halus
                    currentScroll += (targetScroll - currentScroll) * 0.14;
                    if (Math.abs(targetScroll - currentScroll) < 0.15) {
                        currentScroll = targetScroll;
                    }

                    const vh = window.innerHeight || 800;

                    // Scroll progress bar di atas navbar
                    if (progressBar) {
                        const maxScroll = Math.max(1, document.documentElement.scrollHeight - vh);
                        const ratio = clamp(currentScroll / maxScroll, 0, 1);
                        progressBar.style.transform = 'scaleX(' + ratio.toFixed(4) + ')';
                    }

                    // Elemen dengan data-parallax="<speed>"
                    for (let i = 0; i < parallaxEls.length; i++) {
                        const el = parallaxEls[i];
                        const speed = parseFloat(el.getAttribute('data-parallax')) || 0;
                        const rect = el.getBoundingClientRect();
                        const centerOffset = (rect.top + rect.height * 0.5) - vh * 0.5;
                        const y = clamp(centerOffset * speed, -70, 70);
                        el.style.transform = 'translate3d(0, ' + y.toFixed(2) + 'px, 0)';
                    }

                    // Gambar di dalam frame dengan data-parallax-img="<speed>"
                    for (let i = 0; i < parallaxImgs.length; i++) {
                        const img = parallaxImgs[i];
                        const speed = parseFloat(img.getAttribute('data-parallax-img')) || 0.18;
                        const parent = img.parentElement || img;
                        const rect = parent.getBoundingClientRect();
                        if (rect.bottom < -100 || rect.top > vh + 100) continue;
                        const centerOffset = (rect.top + rect.height * 0.5) - vh * 0.5;
                        const y = clamp(centerOffset * -speed, -65, 65);
                        img.style.transform = 'translate3d(0, ' + y.toFixed(2) + 'px, 0) scale(1.18)';
                    }

                    // Efek scale-up & elevasi halus saat di-scroll (Hero Mockup)
                    for (let i = 0; i < parallaxScales.length; i++) {
                        const el = parallaxScales[i];
                        const rect = el.getBoundingClientRect();
                        const progress = clamp(1 - (rect.top / (vh * 0.85)), 0, 1);
                        const scale = 0.965 + (progress * 0.035);
                        const yLift = (1 - progress) * 18;
                        el.style.transform = 'translate3d(0, ' + yLift.toFixed(2) + 'px, 0) scale(' + scale.toFixed(4) + ')';
                    }

                    if (Math.abs(targetScroll - currentScroll) >= 0.15) {
                        rafId = requestAnimationFrame(updateParallax);
                    } else {
                        rafId = null;
                    }
                }

                function onScroll() {
                    targetScroll = window.scrollY || 0;
                    if (!rafId) {
                        rafId = requestAnimationFrame(updateParallax);
                    }
                }

                window.addEventListener('scroll', onScroll, { passive: true });
                window.addEventListener('resize', onScroll, { passive: true });
                updateParallax();

                // ============================================================
                // 3. INTERACTIVE 3D CURSOR PARALLAX (Khusus Desktop pada Kartu/Mockup)
                // ============================================================
                if (window.matchMedia('(pointer: fine)').matches) {
                    document.querySelectorAll('[data-tilt]').forEach(function (card) {
                        const maxTilt = parseFloat(card.getAttribute('data-tilt')) || 2.2;
                        card.addEventListener('mousemove', function (e) {
                            const rect = card.getBoundingClientRect();
                            const x = (e.clientX - rect.left) / rect.width - 0.5;
                            const y = (e.clientY - rect.top) / rect.height - 0.5;
                            card.style.transition = 'transform 0.15s ease-out';
                            card.style.transform = 'perspective(1200px) rotateX(' + (-y * maxTilt).toFixed(2) + 'deg) rotateY(' + (x * maxTilt).toFixed(2) + 'deg) translate3d(0, -2px, 0)';
                        });
                        card.addEventListener('mouseleave', function () {
                            card.style.transition = 'transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)';
                            card.style.transform = 'perspective(1200px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0)';
                        });
                    });
                }
            }
        });
    </script>
</body>
</html>
