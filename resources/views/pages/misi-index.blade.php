@extends('layouts.app')

@section('title', 'Misi Harian — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center space-y-3">
        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
            🚀 Gamifikasi Belajar
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Misi Harian EnergiKita</h1>
        <p class="text-slate-600 text-base">
            Selesaikan misi harian berikut untuk melipatgandakan perolehan XP dan raih badge pencapaian tertinggi.
        </p>
    </div>

    <div class="space-y-4">
        @forelse($missions as $m)
            <div class="p-6 rounded-2xl bg-white border border-slate-200 flex items-center justify-between shadow-xs hover:shadow-md transition">
                <div class="space-y-1 pr-4">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-800 font-bold text-xs flex items-center justify-center">✓</span>
                        <h3 class="text-base font-bold text-slate-900">{{ $m->title }}</h3>
                    </div>
                    <p class="text-xs text-slate-600 pl-8">{{ $m->criteria }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="px-3 py-1.5 rounded-lg bg-amber-500 text-slate-950 font-black text-xs shadow-xs">
                        +{{ $m->xp }} XP
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-slate-500">
                Belum ada misi harian yang aktif.
            </div>
        @endforelse
    </div>
</div>
@endsection

