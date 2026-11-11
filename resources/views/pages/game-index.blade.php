@extends('layouts.app')

@section('title', 'Game Edukasi Energi — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center space-y-6">
    <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-4xl shadow-inner">
        🎮
    </div>
    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
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

