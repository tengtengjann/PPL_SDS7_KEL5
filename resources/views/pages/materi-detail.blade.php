@extends('layouts.app')

@section('title', $material->title . ' — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="flex items-center gap-2 text-xs text-stone-400">
        <a href="{{ route('home') }}" class="hover:text-stone-900 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('materials.index') }}" class="hover:text-stone-900 transition">Materi Belajar</a>
        <span>/</span>
        <span class="text-stone-900 font-medium truncate max-w-[200px]">{{ $material->title }}</span>
    </div>

    <div class="space-y-3" data-parallax="-0.04">
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold bg-[#E6EFE0] text-[#2D3A1E] border border-[#CFDEC4]">
            {{ $material->category }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-normal font-serif text-stone-950 tracking-tight leading-tight">
            {{ $material->title }}<span class="text-[#4E6635]">.</span>
        </h1>
        <p class="text-sm sm:text-base text-stone-500 font-normal leading-relaxed">
            {{ $material->description }}
        </p>
    </div>

    @if(!empty($material->image))
        <div class="relative rounded-3xl overflow-hidden border border-stone-200 bg-stone-100 shadow-sm h-64 sm:h-80 flex items-center justify-center">
            @if(file_exists(public_path('storage/' . $material->image)))
                <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->title }}" data-parallax-img="0.18" class="w-full h-full object-cover">
            @elseif(file_exists(public_path($material->image)))
                <img src="{{ asset($material->image) }}" alt="{{ $material->title }}" data-parallax-img="0.18" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('images/banner-landscape.jpg') }}" alt="{{ $material->title }}" data-parallax-img="0.18" class="w-full h-full object-cover object-[center_65%]">
            @endif
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-stone-200 p-8 sm:p-10 shadow-xs space-y-8 leading-relaxed text-stone-700">
        <div class="prose max-w-none text-sm sm:text-base text-stone-700 leading-relaxed font-normal">
            <p>{{ $material->body }}</p>
        </div>

        <div class="pt-6 border-t border-stone-100 flex flex-wrap items-center justify-between gap-4">
            <span class="text-xs text-stone-400">Modul Edukasi SDG 7 &bull; EnergiKita &bull; Organisasi SRE</span>
            @if($material->quizzes->count() > 0)
                <a href="{{ route('quiz.show', $material->quizzes->first()) }}" class="px-6 py-2.5 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white font-medium text-xs shadow-xs transition inline-flex items-center gap-1.5">
                    <span>Ikuti Kuis Materi Ini</span>
                    <span>↗</span>
                </a>
            @else
                <a href="{{ route('materials.index') }}" class="px-6 py-2.5 rounded-full bg-[#E6EFE0] hover:bg-[#D5E1CA] text-[#2D3A1E] font-semibold text-xs transition inline-flex items-center gap-1.5">
                    <span>Kembali ke Daftar Materi</span>
                    <span>↗</span>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

