@extends('layouts.app')

@section('title', 'Katalog Badge Pencapaian — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header Section --}}
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold mb-4">
            <span>🏅</span>
            <span>Sistem Badge Pencapaian Belajar</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Koleksi <span class="text-emerald-600">Badge</span> EnergiKita
        </h1>
        <p class="mt-3 text-base text-slate-500 max-w-2xl mx-auto">
            Raih badge pencapaian dengan menyelesaikan modul, kuis, dan mengumpulkan XP. 
            Setiap badge membuktikan dedikasi Anda terhadap edukasi energi bersih!
        </p>
    </div>

    {{-- Statistik User --}}
    @auth
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center shadow-xs">
            <p class="text-3xl font-black text-emerald-600">{{ $userStats['modules_completed'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1">Modul Selesai</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center shadow-xs">
            <p class="text-3xl font-black text-blue-600">{{ $userStats['quizzes_passed'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1">Kuis Dilalui</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center shadow-xs">
            <p class="text-3xl font-black text-amber-600">{{ $userStats['xp_reached'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1">Total XP</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center shadow-xs">
            <p class="text-3xl font-black text-purple-600">{{ count($earnedBadgeIds) }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1">Badge Diraih</p>
        </div>
    </div>
    @endauth

    {{-- Grid Badge --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($badges as $badge)
            @php
                $isEarned = in_array($badge->id, $earnedBadgeIds);
                $earnedAt = $isEarned && isset($earnedBadges[$badge->id]) 
                            ? $earnedBadges[$badge->id]->earned_at 
                            : null;
            @endphp

            <div 
                class="group relative bg-white rounded-2xl border-2 transition-all duration-300 overflow-hidden
                       {{ $isEarned 
                          ? 'border-emerald-300 shadow-md shadow-emerald-100 hover:shadow-lg hover:shadow-emerald-200' 
                          : 'border-slate-200 hover:border-slate-300 shadow-xs hover:shadow-md' }}"
                x-data="{ showDetail: false }"
            >
                {{-- Earned Ribbon --}}
                @if($isEarned)
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Diraih
                    </span>
                </div>
                @else
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Terkunci
                    </span>
                </div>
                @endif

                <div class="p-6">
                    {{-- Badge Icon --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex items-center justify-center text-3xl
                                    {{ $isEarned 
                                       ? 'bg-gradient-to-br from-emerald-100 to-teal-100 shadow-inner' 
                                       : 'bg-slate-100 grayscale opacity-60' }}">
                            {{ $badge->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold {{ $isEarned ? 'text-slate-900' : 'text-slate-500' }} truncate">
                                {{ $badge->name }}
                            </h3>
                            <p class="mt-1 text-sm {{ $isEarned ? 'text-slate-600' : 'text-slate-400' }} line-clamp-2">
                                {{ $badge->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Kriteria --}}
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                     {{ $isEarned 
                                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                                        : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $badge->criteria_label }}
                        </span>
                    </div>

                    {{-- Tanggal Diraih --}}
                    @if($isEarned && $earnedAt)
                    <div class="mt-3 text-xs text-emerald-600 font-medium">
                        ✨ Diraih pada {{ $earnedAt->translatedFormat('d F Y, H:i') }}
                    </div>
                    @endif

                    {{-- Tombol Lihat Detail --}}
                    <button 
                        @click="showDetail = !showDetail"
                        class="mt-4 w-full py-2.5 rounded-xl text-xs font-bold transition-all
                               {{ $isEarned 
                                  ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' 
                                  : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
                    >
                        <span x-text="showDetail ? 'Tutup Detail' : 'Lihat Syarat Detail'"></span>
                    </button>

                    {{-- Detail Panel (Collapsible) --}}
                    <div 
                        x-show="showDetail" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3"
                        x-cloak
                    >
                        <h4 class="text-sm font-bold text-slate-800">📋 Syarat Mendapatkan Badge</h4>
                        <div class="space-y-2 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isEarned ? 'bg-emerald-500' : 'bg-slate-300' }} flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ $isEarned ? '✓' : '·' }}
                                </span>
                                <span>Tipe: <strong>{{ ucwords(str_replace('_', ' ', $badge->criteria_type)) }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isEarned ? 'bg-emerald-500' : 'bg-slate-300' }} flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ $isEarned ? '✓' : '·' }}
                                </span>
                                <span>Target: <strong>{{ $badge->criteria_value }}</strong></span>
                            </div>
                            @auth
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full {{ $isEarned ? 'bg-emerald-500' : 'bg-amber-400' }} flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ $isEarned ? '✓' : '!' }}
                                </span>
                                <span>Progress Anda: <strong>{{ $userStats[$badge->criteria_type] ?? 0 }} / {{ $badge->criteria_value }}</strong></span>
                            </div>

                            {{-- Progress Bar --}}
                            @php
                                $progress = min(100, round(($userStats[$badge->criteria_type] ?? 0) / max(1, $badge->criteria_value) * 100));
                            @endphp
                            <div class="w-full bg-slate-200 rounded-full h-2.5 mt-1">
                                <div class="h-2.5 rounded-full transition-all duration-500 {{ $isEarned ? 'bg-emerald-500' : 'bg-amber-400' }}" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 text-right">{{ $progress }}% tercapai</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 text-slate-400">
                <p class="text-5xl mb-4">🏅</p>
                <p class="text-lg font-bold">Belum Ada Badge</p>
                <p class="text-sm">Admin belum menambahkan badge pencapaian.</p>
            </div>
        @endforelse
    </div>

    {{-- CTA Section --}}
    <div class="mt-12 text-center">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 sm:p-12 text-white">
            <h2 class="text-2xl font-extrabold">Mulai Raih Badge Anda! 🚀</h2>
            <p class="mt-2 text-emerald-100 max-w-lg mx-auto">
                Pelajari materi energi, kerjakan kuis, dan selesaikan misi untuk membuka semua badge pencapaian.
            </p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('materials.index') }}" class="px-6 py-3 rounded-xl bg-white text-emerald-700 font-bold text-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                    📖 Mulai Belajar
                </a>
                <a href="{{ route('quizzes.index') }}" class="px-6 py-3 rounded-xl bg-emerald-500/30 text-white font-bold text-sm border border-white/30 hover:bg-emerald-500/50 transition transform hover:-translate-y-0.5">
                    📝 Kerjakan Kuis
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Alpine.js CDN (jika belum ter-include secara global) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
