@extends('layouts.app')

@section('title', 'Belajar & Modul Materi — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
            📚 Kurikulum EBT
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Modul Materi Energi Bersih</h1>
        <p class="text-slate-600 text-base">
            Pelajari konsep fundamental dan implementasi teknologi energi terbarukan di Indonesia sesuai prinsip SDG 7.
        </p>
    </div>

    <!-- Filter Kategori Tabs -->
    <div class="flex flex-wrap items-center justify-center gap-2">
        <a href="{{ route('materials.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-sm">
            Semua Modul
        </a>
        <a href="{{ route('materials.category', 'energi-surya') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition">
            ☀️ Energi Surya
        </a>
        <a href="{{ route('materials.category', 'energi-angin') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition">
            💨 Energi Angin
        </a>
        <a href="{{ route('materials.category', 'energi-air') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition">
            💧 Energi Air
        </a>
        <a href="{{ route('materials.category', 'hemat-energi') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition">
            ⚡ Hemat Energi
        </a>
    </div>

    <!-- Grid Materi -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materials as $item)
            <div class="rounded-2xl bg-white border border-slate-200 p-6 flex flex-col justify-between shadow-xs hover:shadow-lg transition">
                <div class="space-y-3">
                    <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold 
                        {{ $item->category === 'Energi Surya' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $item->category === 'Energi Angin' ? 'bg-sky-100 text-sky-800' : '' }}
                        {{ $item->category === 'Energi Air' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $item->category === 'Hemat Energi' || $item->category === 'Efisiensi Energi' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                        {{ $item->category }}
                    </span>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug">{{ $item->title }}</h3>
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">{{ $item->description }}</p>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">10 Menit Baca</span>
                    <a href="{{ route('materials.show', $item->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                        <span>Buka Materi</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada materi terdaftar.
            </div>
        @endforelse
    </div>
</div>
@endsection

