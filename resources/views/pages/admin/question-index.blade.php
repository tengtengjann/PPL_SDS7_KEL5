@extends('layouts.app')

@section('title', 'Admin — Kelola Soal Kuis — EnergiKita')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    {{-- Header & Breadcrumb (Area Editorial Style) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 pb-6 border-b border-stone-200">
        <div class="space-y-2">
            <a href="{{ route('admin.materials.index') }}" class="inline-flex items-center gap-1.5 text-xs text-stone-500 hover:text-stone-900 font-medium transition">
                <span>&larr;</span>
                <span>Kembali ke Daftar Materi</span>
            </a>
            <div class="pt-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#4E6635] block">
                    Modul: {{ $quiz->material->title ?? 'Umum' }} &bull; PBI-015
                </span>
                <h1 class="text-3xl sm:text-5xl font-normal font-serif text-stone-950 tracking-tight mt-1">
                    {{ $quiz->title }}<span class="text-[#4E6635]">.</span>
                </h1>
                <p class="text-sm text-stone-500 mt-2 font-normal leading-relaxed max-w-xl">
                    Kelola butir soal pilihan ganda, tentukan opsi jawaban, kunci jawaban yang benar, serta pembahasan evaluasi kuis.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($quiz->questions->count() > 0)
                <a href="{{ route('quiz.show', $quiz) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 text-xs font-medium transition shadow-xs">
                    <span>Uji Coba Kuis</span>
                    <span class="text-xs">↗</span>
                </a>
            @endif
            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" 
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white text-xs font-medium shadow-md shadow-[#2D3A1E]/15 transition transform hover:-translate-y-0.5">
                <span>Tambah Soal Baru</span>
                <span class="text-sm">↗</span>
            </a>
        </div>
    </div>

    {{-- Info Card Ringkas (Warm Sand Frame) --}}
    <div class="bg-[#EDE7DD] rounded-3xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-stone-800 shadow-xs">
        <div class="space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-widest text-stone-500">Evaluasi Otomatis</span>
            <p class="text-stone-700">Terdapat <strong>{{ $quiz->questions->count() }} soal pilihan ganda</strong>. Setiap butir soal dinilai otomatis secara real-time saat mahasiswa menyelesaikan sesi kuis.</p>
        </div>
        <span class="px-4 py-1.5 rounded-full bg-[#E6EFE0] text-[#2D3A1E] font-bold text-xs shrink-0">
            {{ $quiz->questions->count() }} Soal Aktif
        </span>
    </div>

    {{-- Daftar Soal Kuis --}}
    <div class="space-y-6">
        @forelse($quiz->questions as $index => $q)
            <div class="bg-white rounded-3xl border border-stone-200 shadow-xs p-6 sm:p-8 space-y-6 hover:border-stone-300 transition">
                
                {{-- Header Soal & Aksi --}}
                <div class="flex items-start justify-between gap-4 pb-4 border-b border-stone-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-[#2D3A1E] text-white font-serif font-light text-sm flex items-center justify-center">
                            {{ sprintf('%02d', $index + 1) }}
                        </span>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Butir Soal Pilihan Ganda</span>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Edit Soal --}}
                        <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $q]) }}" 
                           class="px-4 py-1.5 rounded-full bg-stone-100 text-stone-800 text-xs font-medium hover:bg-stone-200 transition">
                            Edit Soal
                        </a>

                        {{-- Hapus Soal --}}
                        <form method="POST" action="{{ route('admin.quizzes.questions.destroy', [$quiz, $q]) }}" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal nomor {{ $index + 1 }} ini?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-1.5 rounded-full bg-rose-50 text-rose-700 text-xs font-medium hover:bg-rose-100 border border-rose-200/60 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Pertanyaan --}}
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-stone-900 leading-relaxed font-serif">
                        {{ $q->question }}
                    </h3>
                </div>

                {{-- Opsi Pilihan Ganda & Penanda Kunci Jawaban --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                    @foreach(($q->options ?? []) as $key => $optionText)
                        @php
                            $isCorrect = (strtoupper($key) === strtoupper($q->answer));
                        @endphp
                        <div class="p-4 rounded-2xl border text-xs flex items-start gap-3 transition {{ $isCorrect ? 'bg-[#F2F7EC] border-[#CFDEC4] ring-1 ring-[#8DA878]' : 'bg-stone-50/60 border-stone-200 text-stone-700' }}">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $isCorrect ? 'bg-[#2D3A1E] text-white' : 'bg-stone-200 text-stone-700' }}">
                                {{ $key }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium {{ $isCorrect ? 'font-bold text-[#1E2815]' : 'text-stone-700' }}">
                                    {{ $optionText }}
                                </p>
                                @if($isCorrect)
                                    <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-bold text-[#2D3A1E] uppercase tracking-wider">
                                        ✓ Kunci Jawaban Benar
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pembahasan --}}
                @if(!empty($q->explanation))
                    <div class="p-4 rounded-2xl bg-[#EDE7DD] border border-stone-300/50 text-xs text-stone-800 space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-stone-950">
                            <svg class="w-4 h-4 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Pembahasan Kunci Jawaban:
                        </p>
                        <p class="leading-relaxed text-stone-600">
                            {{ $q->explanation }}
                        </p>
                    </div>
                @endif

            </div>
        @empty
            <div class="bg-white rounded-3xl border border-dashed border-stone-300 p-16 text-center space-y-4">
                <span class="text-4xl block font-serif font-light text-stone-300">00</span>
                <div>
                    <h3 class="text-base font-bold text-stone-900 font-serif">Belum Ada Soal pada Kuis Ini</h3>
                    <p class="text-xs text-stone-500 mt-1 max-w-md mx-auto">
                        Kuis ini belum memiliki pertanyaan pilihan ganda. Tambahkan butir soal beserta kunci jawaban agar mahasiswa dapat menguji pemahaman materi ini.
                    </p>
                </div>
                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" 
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white text-xs font-medium shadow-md shadow-[#2D3A1E]/15 transition">
                    <span>+ Tambah Soal Pertama Sekarang</span>
                    <span class="text-xs">↗</span>
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
