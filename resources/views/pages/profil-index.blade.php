@extends('layouts.app')

@section('title', 'Profil Saya — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- ============================================================
         KARTU PROFIL UTAMA
         ============================================================ --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">

        {{-- Header Gradient --}}
        <div class="relative h-32 bg-gradient-to-r from-emerald-600 via-teal-500 to-amber-400">
            <div class="absolute -bottom-12 left-8">
                <div class="w-24 h-24 rounded-2xl bg-white shadow-lg flex items-center justify-center border-4 border-white">
                    <span class="text-4xl font-black text-emerald-600">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="pt-16 pb-8 px-8 space-y-6">
            <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                            {{ $user->role === 'sre' || $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $user->role === 'sre' || $user->role === 'admin' ? 'Admin SRE' : 'Mahasiswa' }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold px-3 py-1.5 rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                        ⚡ {{ $user->xp ?? 0 }} XP
                    </span>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                        🏅 {{ $user->current_badge ? $user->current_badge->name : 'Energy Beginner' }}
                    </span>
                </div>
            </div>

            {{-- Detail Informasi Akun --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->email }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role / Peran</p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $user->role === 'sre' || $user->role === 'admin' ? 'Organisasi SRE (Admin)' : 'Mahasiswa' }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total XP</p>
                    <p class="text-sm font-semibold text-amber-700">⚡ {{ $user->xp ?? 0 }} Poin Pengalaman</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Badge Diraih</p>
                    <p class="text-sm font-semibold text-purple-700">🎖️ {{ $earnedBadges->count() }} Badge</p>
                </div>
            </div>

            {{-- Navigasi Cepat --}}
            <div class="pt-6 border-t border-slate-100 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                    &larr; Kembali ke Beranda
                </a>
                <a href="{{ route('learning-progress.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-bold border border-blue-200 transition">
                    📊 Progres Belajar
                </a>
                <a href="{{ route('quiz-history.index') }}" class="px-5 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-800 text-xs font-bold border border-indigo-200 transition">
                    📝 Riwayat Kuis
                </a>
                <a href="{{ route('badges.index') }}" class="px-5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 transition">
                    🏅 Katalog Badge
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         KOLEKSI BADGE PENCAPAIAN USER
         ============================================================ --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span>🎖️</span>
                    <span>Koleksi Badge Saya</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">Badge pencapaian yang sudah Anda raih di EnergiKita.</p>
            </div>
            <a href="{{ route('badges.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition hidden sm:inline">
                Lihat Semua &rarr;
            </a>
        </div>

        @if($earnedBadges->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($earnedBadges as $userBadge)
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 hover:shadow-md transition-all duration-200">
                        {{-- Ikon Badge --}}
                        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl border border-emerald-200">
                            {{ $userBadge->badge->icon }}
                        </div>

                        {{-- Info Badge --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 truncate">{{ $userBadge->badge->name }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ $userBadge->badge->description }}</p>
                            <div class="mt-1 flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-[11px] text-emerald-700 font-semibold">
                                    Diraih {{ $userBadge->earned_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- Check Icon --}}
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Ringkasan --}}
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Anda telah meraih <strong class="text-emerald-700">{{ $earnedBadges->count() }}</strong> dari <strong>{{ $totalBadges }}</strong> badge yang tersedia.
                </p>
                @if($earnedBadges->count() < $totalBadges)
                    <span class="text-xs font-bold text-amber-600">
                        {{ $totalBadges - $earnedBadges->count() }} badge lagi menunggu! 🚀
                    </span>
                @else
                    <span class="text-xs font-bold text-emerald-600">
                        🎉 Semua badge berhasil diraih!
                    </span>
                @endif
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <p class="text-5xl mb-4">🔒</p>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Badge</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Mulai pelajari materi dan selesaikan kuis untuk mendapatkan badge pencapaian pertama Anda!
                </p>
                <a href="{{ route('materials.index') }}"
                   class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md transition">
                    📖 Mulai Belajar Sekarang
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
