@extends('layouts.app')

@section('title', 'Kategori ' . $categoryName . ' — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('materials.index') }}" class="hover:text-emerald-600">Materi</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">{{ $categoryName }}</span>
    </div>

    <div class="space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-900">Materi: {{ $categoryName }}</h1>
        <p class="text-slate-600 text-sm">Daftar modul pembelajaran mendalam untuk topik {{ $categoryName }}.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materials as $item)
            <div class="rounded-2xl bg-white border border-slate-200 p-6 flex flex-col justify-between shadow-xs hover:shadow-lg transition">
                <div class="space-y-3">
                    <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800">
                        {{ $item->category }}
                    </span>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug">{{ $item->title }}</h3>
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">{{ $item->description }}</p>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">Modul Pembelajaran</span>
                    <a href="{{ route('materials.show', $item->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                        <span>Baca Selengkapnya</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada materi untuk kategori ini.
            </div>
        @endforelse
    </div>
</div>
@endsection

