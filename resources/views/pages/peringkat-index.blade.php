@extends('layouts.app')

@section('title', 'Papan Peringkat Belajar — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center space-y-3">
        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
            🏆 Leaderboard
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Peringkat Pembelajar Teraktif</h1>
        <p class="text-slate-600 text-base">
            Daftar mahasiswa dengan akumulasi XP tertinggi dari penyelesaian materi dan kuis energi bersih.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="divide-y divide-slate-100">
            @forelse($leaderboard as $index => $u)
                <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50 transition {{ $index === 0 ? 'bg-amber-50/40' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm
                            {{ $index === 0 ? 'bg-amber-400 text-slate-950 shadow-sm' : ($index === 1 ? 'bg-slate-300 text-slate-900' : ($index === 2 ? 'bg-amber-700/60 text-white' : 'text-slate-400')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $u->name }}</p>
                            <p class="text-xs text-slate-500">Mahasiswa Pelajar</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                            Badge: {{ $u->current_badge ? $u->current_badge->name : 'Energy Beginner' }}
                        </span>
                        <span class="text-sm font-black text-amber-600 flex items-center gap-1">
                            ⚡ {{ $u->xp }} XP
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 text-sm">
                    Belum ada data peringkat.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

