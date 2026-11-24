@extends('layouts.app')

@section('title', 'Kuis Cepat Energi Bersih — EnergiKita')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
    <div class="max-w-2xl">
        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">Uji Pemahaman</p>
        <h1 class="mt-4 text-4xl sm:text-5xl text-stone-950">Kuis Cepat Energi Bersih<span class="text-[#4E6635]">.</span></h1>
        <p class="mt-4 text-stone-500">Uji pemahaman Anda seputar materi transisi energi dan kumpulkan XP untuk menaikkan peringkat dan badge.</p>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($quizzes as $quiz)
            @php $count = $quiz->questions->count(); @endphp
            <div class="rounded-[24px] bg-white border border-stone-200 hover:border-stone-300 p-7 flex flex-col transition">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-600">{{ $quiz->material->category ?? 'Energi Terbarukan' }}</span>
                    <span class="text-[#4E6635] font-semibold">+50 XP</span>
                </div>
                <h3 class="mt-6 text-xl font-serif text-stone-950 leading-snug">{{ $quiz->title }}</h3>
                <p class="mt-3 text-sm text-stone-500">Modul: <span class="text-stone-700">{{ $quiz->material->title ?? '-' }}</span></p>
                <p class="mt-1 text-xs text-stone-400">{{ $count }} Pertanyaan Pilihan Ganda</p>

                <div class="mt-auto pt-8">
                    @if($count > 0)
                        <a href="{{ route('quiz.show', $quiz) }}"
                           class="flex items-center justify-between w-full px-5 py-3 rounded-full bg-[#2D3A1E] hover:bg-[#1F2915] text-white text-xs font-medium transition">
                            <span>Mulai Kerjakan Kuis</span><span aria-hidden="true">↗</span>
                        </a>
                    @else
                        <span class="block w-full px-5 py-3 rounded-full bg-stone-100 text-stone-400 text-xs text-center">Soal belum tersedia</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-stone-500 border border-dashed border-stone-300 rounded-[24px]">
                Belum ada kuis tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection
