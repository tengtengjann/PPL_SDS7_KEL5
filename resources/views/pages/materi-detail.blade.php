@extends('layouts.app')

@section('title', $material->title . ' — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('materials.index') }}" class="hover:text-emerald-600">Materi</a>
        <span>/</span>
        <span class="text-slate-900 font-bold truncate max-w-[200px]">{{ $material->title }}</span>
    </div>

    <div class="space-y-4">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
            {{ $material->category }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
            {{ $material->title }}
        </h1>
        <p class="text-base text-slate-600 font-medium">
            {{ $material->description }}
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-6 leading-relaxed text-slate-700">
        <div class="prose max-w-none text-base">
            <p>{{ $material->body }}</p>
        </div>

        <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <span class="text-xs text-slate-400">Modul Edukasi SDG 7 &bull; EnergiKita</span>
            @if($material->quizzes->count() > 0)
                <a href="{{ route('quizzes.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20">
                    Ikuti Kuis Materi Ini &rarr;
                </a>
            @else
                <a href="{{ route('materials.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20">
                    Kembali ke Daftar Materi &rarr;
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

