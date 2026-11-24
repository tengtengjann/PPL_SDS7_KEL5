@extends('layouts.app')

@section('title', 'Katalog Badge Pencapaian — EnergiKita')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 space-y-12">

    {{-- Header Section --}}
    <div class="max-w-2xl">
        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">
            Sistem Badge Pencapaian Belajar
        </p>
        <h1 class="mt-4 text-4xl sm:text-5xl text-stone-950">
            Koleksi Badge EnergiKita<span class="text-[#4E6635]">.</span>
        </h1>
        <p class="mt-4 text-stone-500 leading-relaxed">
            Raih badge pencapaian dengan menyelesaikan modul, kuis, dan mengumpulkan XP. Setiap badge membuktikan dedikasi Anda terhadap edukasi energi bersih.
        </p>
    </div>

    {{-- Statistik User --}}
    @auth
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-[24px] border border-stone-200 p-6">
            <p class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold">Modul Selesai</p>
            <p class="mt-2 text-3xl font-serif text-stone-950 tabular-nums">{{ $userStats['modules_completed'] }}</p>
        </div>
        <div class="bg-white rounded-[24px] border border-stone-200 p-6">
            <p class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold">Kuis Dilalui</p>
            <p class="mt-2 text-3xl font-serif text-stone-950 tabular-nums">{{ $userStats['quizzes_passed'] }}</p>
        </div>
        <div class="bg-white rounded-[24px] border border-stone-200 p-6">
            <p class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold">Total XP</p>
            <p class="mt-2 text-3xl font-serif text-[#2D3A1E] tabular-nums">{{ number_format($userStats['xp_reached']) }}</p>
        </div>
        <div class="bg-white rounded-[24px] border border-stone-200 p-6">
            <p class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold">Badge Diraih</p>
            <p class="mt-2 text-3xl font-serif text-[#2D3A1E] tabular-nums">{{ count($earnedBadgeIds) }} <span class="text-base text-stone-400">/ {{ $badges->count() }}</span></p>
        </div>
    </div>
    @endauth

    {{-- Grid Badge --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($badges as $badge)
            @php
                $isEarned = in_array($badge->id, $earnedBadgeIds);
                $earnedAt = $isEarned && isset($earnedBadges[$badge->id])
                            ? $earnedBadges[$badge->id]->earned_at
                            : null;
                $progress = min(100, round(($userStats[$badge->criteria_type] ?? 0) / max(1, $badge->criteria_value) * 100));
            @endphp

            <div class="group relative bg-white rounded-[24px] border transition-all duration-200 p-7 flex flex-col justify-between {{ $isEarned ? 'border-[#8EA873] shadow-sm' : 'border-stone-200 hover:border-stone-300' }}">
                <div>
                    {{-- Baris Atas: Monogram Badge & Status Pill --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-serif text-2xl shrink-0 {{ $isEarned ? 'bg-[#E6EFE0] text-[#2D3A1E]' : 'bg-stone-100 text-stone-400' }}">
                            {{ strtoupper(substr($badge->name, 0, 1)) }}
                        </div>

                        @if($isEarned)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#E6EFE0] text-[#2D3A1E] text-[11px] font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Diraih
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-stone-100 text-stone-500 text-[11px] font-medium">
                                Terkunci
                            </span>
                        @endif
                    </div>

                    {{-- Nama & Deskripsi --}}
                    <h3 class="mt-5 text-xl font-serif {{ $isEarned ? 'text-stone-950' : 'text-stone-600' }}">
                        {{ $badge->name }}
                    </h3>
                    <p class="mt-2 text-sm text-stone-500 leading-relaxed">
                        {{ $badge->description }}
                    </p>
                </div>

                <div class="mt-6 pt-5 border-t border-stone-100 space-y-3">
                    {{-- Kriteria & Progress Bar --}}
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-medium text-stone-700">{{ $badge->criteria_label }}</span>
                        @auth
                            <span class="tabular-nums text-stone-500">{{ $userStats[$badge->criteria_type] ?? 0 }} / {{ $badge->criteria_value }} ({{ $progress }}%)</span>
                        @endauth
                    </div>

                    @auth
                        <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $isEarned ? 'bg-[#2D3A1E]' : 'bg-[#8EA873]' }}"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    @endauth

                    @if($isEarned && $earnedAt)
                        <p class="text-[11px] text-[#4E6635] font-medium">
                            Diraih pada {{ $earnedAt->translatedFormat('d F Y') }}
                        </p>
                    @endif

                    {{-- Detail Syarat (Native details - selalu berfungsi tanpa bergantung CDN) --}}
                    <details class="pt-1 text-xs text-stone-600">
                        <summary class="cursor-pointer font-semibold text-[#2D3A1E] hover:underline select-none">
                            Lihat Syarat Detail
                        </summary>
                        <div class="mt-3 p-3.5 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-1.5">
                            <p class="font-semibold text-stone-800">Syarat Mendapatkan Badge</p>
                            <p>Tipe: <strong class="text-stone-800">{{ ucwords(str_replace('_', ' ', $badge->criteria_type)) }}</strong></p>
                            <p>Target: <strong class="text-stone-800">{{ $badge->criteria_value }}</strong></p>
                            @auth
                                <p>Progres Anda: <strong class="text-stone-800">{{ $userStats[$badge->criteria_type] ?? 0 }} / {{ $badge->criteria_value }}</strong></p>
                            @endauth
                        </div>
                    </details>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-[24px] border border-dashed border-stone-300 text-stone-500">
                <p class="text-3xl mb-2 text-stone-300 font-serif">00</p>
                <p class="text-lg font-serif text-stone-800">Belum Ada Badge</p>
                <p class="text-sm mt-1">Admin belum menambahkan badge pencapaian.</p>
            </div>
        @endforelse
    </div>

    {{-- CTA Section --}}
    <div class="bg-[#2D3A1E] rounded-[28px] p-8 sm:p-12 text-center text-white space-y-4">
        <h2 class="text-3xl sm:text-4xl font-serif">Mulai raih badge Anda.</h2>
        <p class="text-stone-300 max-w-lg mx-auto text-sm leading-relaxed">
            Pelajari materi energi, kerjakan kuis, dan selesaikan misi untuk membuka semua badge pencapaian.
        </p>
        <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('materials.index') }}" class="px-6 py-3 rounded-full bg-[#E6EFE0] hover:bg-white text-[#2D3A1E] font-medium text-xs transition">
                Mulai Belajar ↗
            </a>
            <a href="{{ route('quizzes.index') }}" class="px-6 py-3 rounded-full bg-white/10 text-white font-medium text-xs border border-white/20 hover:bg-white/20 transition">
                Kerjakan Kuis ↗
            </a>
        </div>
    </div>

</div>
@endsection
