@extends('layouts.app')

@section('title', 'Game Edukasi Energi — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center space-y-6">
    <div class="w-20 h-20 mx-auto rounded-3xl bg-[#E6EFE0] text-[#2D3A1E] flex items-center justify-center shadow-xs">
        <svg class="w-10 h-10 text-[#2D3A1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
    </div>
    <span class="px-3 py-1 rounded-full bg-[#E6EFE0] text-[#2D3A1E] text-xs font-bold uppercase tracking-wider">
        Fitur Sprint Berikutnya
    </span>
    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Game Simulasi Jaringan EBT</h1>
    <p class="text-slate-600 max-w-xl mx-auto text-base leading-relaxed">
        Fitur mini-game simulasi pengelolaan jaringan pembangkit listrik tenaga surya, angin, dan hidro sedang dalam tahap pengerjaan oleh tim pengembang Sprint berikutnya.
    </p>
    <div>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition">
            &larr; Kembali ke Beranda
        </a>
    </div>
</div>
@endsection

