@extends('layouts.app')

@section('title', 'Profil Pengguna — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-black text-3xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                {{ strtoupper(substr($user->name ?? 'G', 0, 1)) }}
            </div>
            <div class="space-y-1 flex-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-900">{{ $user->name ?? 'Pengunjung' }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ ($user->role ?? '') === 'sre' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ ($user->role ?? '') === 'sre' ? 'Organisasi SRE (Admin)' : 'Mahasiswa' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">{{ $user->email ?? '-' }}</p>
                <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                        ⚡ {{ $user->xp ?? 0 }} XP
                    </span>
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                        🏅 Badge: {{ $user->current_badge ? $user->current_badge->name : 'Energy Beginner' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex flex-wrap gap-3">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                &larr; Kembali ke Beranda
            </a>
            <a href="{{ route('materi.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20">
                Lanjutkan Belajar Materi
            </a>
        </div>
    </div>
</div>
@endsection

