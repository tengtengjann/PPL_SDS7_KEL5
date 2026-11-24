@extends('layouts.app')

@section('title', ($isEditing ? 'Edit Soal Kuis' : 'Tambah Soal Baru') . ' — Admin Organisasi SRE')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- Breadcrumb & Header (Area Editorial Style) --}}
    <div class="space-y-2">
        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="inline-flex items-center gap-1.5 text-xs text-stone-500 hover:text-stone-900 font-medium transition">
            <span>&larr;</span>
            <span>Kembali ke Daftar Soal Kuis</span>
        </a>
        <div class="pt-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-[#4E6635] block">
                Kuis: {{ $quiz->title }} &bull; PBI-015
            </span>
            <h1 class="text-3xl sm:text-5xl font-normal font-serif text-stone-950 tracking-tight mt-1">
                {{ $isEditing ? 'Edit Soal Pilihan Ganda.' : 'Tambah Soal Pilihan Ganda Baru.' }}
            </h1>
            <p class="text-sm text-stone-500 mt-2 font-normal leading-relaxed">
                {{ $isEditing ? 'Perbarui pertanyaan, opsi pilihan jawaban, kunci jawaban yang benar, atau pembahasan.' : 'Tuliskan pertanyaan, sediakan 4 pilihan jawaban, dan tandai kunci jawaban yang benar.' }}
            </p>
        </div>
    </div>

    {{-- Kartu Form Soal --}}
    <div class="bg-white rounded-3xl border border-stone-200 shadow-xs p-6 sm:p-10">
        <form method="POST" 
              action="{{ $isEditing ? route('admin.quizzes.questions.update', [$quiz, $question]) : route('admin.quizzes.questions.store', $quiz) }}"
              class="space-y-7">
            @csrf
            @if($isEditing)
                @method('PUT')
            @endif

            {{-- 1. Pertanyaan --}}
            <div class="space-y-1.5">
                <label for="question" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Pertanyaan Soal <span class="text-rose-500">*</span>
                </label>
                <textarea name="question" id="question" rows="4" required
                          placeholder="Tuliskan pertanyaan pilihan ganda secara jelas dan objektif..."
                          class="w-full px-4 py-3.5 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 leading-relaxed font-serif @error('question') border-rose-400 bg-rose-50/30 @enderror">{{ old('question', $question->question ?? '') }}</textarea>
                @error('question')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Pilihan Jawaban (A, B, C, D) & Penentu Kunci Jawaban --}}
            <div class="space-y-4 pt-4 border-t border-stone-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                        Opsi Pilihan Jawaban & Kunci Jawaban Benar <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-xs text-stone-500 mt-1">
                        Isi keempat pilihan jawaban di bawah ini, lalu <strong>pilih tombol radio</strong> pada opsi yang merupakan <strong>kunci jawaban yang benar</strong>.
                    </p>
                    @error('answer')
                        <p class="mt-2 text-xs text-rose-600 font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @php
                    $currentAnswer = old('answer', $question->answer ?? 'A');
                    $currentOptions = old('options', $question->options ?? []);
                @endphp

                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D'] as $optKey)
                        @php
                            $optValue = $currentOptions[$optKey] ?? '';
                            $isChecked = ($currentAnswer === $optKey);
                        @endphp
                        <div class="p-3.5 rounded-2xl border transition {{ $isChecked ? 'bg-[#F2F7EC] border-[#CFDEC4] ring-1 ring-[#8DA878]' : 'bg-stone-50/50 border-stone-200' }}">
                            <div class="flex items-center gap-3">
                                {{-- Radio Kunci Jawaban --}}
                                <label class="flex items-center gap-2 cursor-pointer shrink-0">
                                    <input type="radio" name="answer" value="{{ $optKey }}" {{ $isChecked ? 'checked' : '' }} required
                                           class="w-4 h-4 text-[#2D3A1E] border-stone-300 focus:ring-0 cursor-pointer">
                                    <span class="w-6 h-6 rounded-full font-bold text-xs flex items-center justify-center {{ $isChecked ? 'bg-[#2D3A1E] text-white' : 'bg-stone-200 text-stone-700' }}">
                                        {{ $optKey }}
                                    </span>
                                </label>

                                {{-- Input Teks Pilihan --}}
                                <div class="flex-1">
                                    <input type="text" name="options[{{ $optKey }}]" required
                                           value="{{ $optValue }}"
                                           placeholder="Tuliskan teks jawaban untuk pilihan {{ $optKey }}..."
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs sm:text-sm bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 @error('options.' . $optKey) border-rose-400 @enderror">
                                </div>
                            </div>
                            @error('options.' . $optKey)
                                <p class="mt-1 text-xs text-rose-600 font-medium pl-10">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Pembahasan Jawaban (Opsional) --}}
            <div class="space-y-1.5 pt-4 border-t border-stone-100">
                <label for="explanation" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Pembahasan / Penjelasan Jawaban (Opsional)
                </label>
                <textarea name="explanation" id="explanation" rows="3"
                          placeholder="Jelaskan mengapa kunci jawaban tersebut benar agar mahasiswa dapat belajar dari evaluasi kuis ini..."
                          class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 leading-relaxed resize-none @error('explanation') border-rose-400 bg-rose-50/30 @enderror">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                <p class="text-[11px] text-stone-400">Ditampilkan kepada mahasiswa setelah mereka mengumpulkan jawaban kuis.</p>
                @error('explanation')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi Pill --}}
            <div class="pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" 
                   class="px-6 py-2.5 rounded-full border border-stone-300 text-stone-600 hover:bg-stone-50 text-xs font-medium transition">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white text-xs sm:text-sm font-medium shadow-md shadow-[#2D3A1E]/15 transition transform hover:-translate-y-0.5">
                    <span>{{ $isEditing ? 'Perbarui Soal Kuis' : 'Simpan Soal Kuis' }}</span>
                    <span class="text-sm">↗</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
