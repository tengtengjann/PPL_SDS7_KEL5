@extends('layouts.app')

@section('title', $quiz->title . ' — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header Kuis --}}
    <div class="mb-10">
        <a href="{{ route('quiz.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Kembali ke Daftar Kuis
        </a>
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $quiz->material->category ?? 'Energi Terbarukan' }}
                    </span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-[#EDE7DD] text-stone-800 border border-stone-200">
                        +50 XP
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $quiz->title }}</h1>
                <p class="text-sm text-slate-500">
                    Berdasarkan modul: <strong class="text-slate-700">{{ $quiz->material->title ?? '-' }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-200">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                <span class="text-sm font-bold text-slate-700">{{ $quiz->questions->count() }} Soal</span>
            </div>
        </div>
    </div>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold text-rose-700 text-sm">Harap jawab semua soal sebelum mengirim!</span>
            </div>
            <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Kuis --}}
    <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quizForm">
        @csrf

        <div class="space-y-6">
            @foreach ($quiz->questions as $index => $question)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden hover:shadow-md transition"
                     id="question-{{ $question->id }}">
                    {{-- Nomor Soal --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Pertanyaan {{ $index + 1 }} dari {{ $quiz->questions->count() }}
                            </span>
                        </div>
                        @error("answers.{$question->id}")
                            <span class="text-xs font-bold text-rose-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Belum dijawab
                            </span>
                        @enderror
                    </div>

                    {{-- Teks Soal --}}
                    <div class="px-6 pt-5 pb-3">
                        <p class="text-base font-semibold text-slate-800 leading-relaxed">
                            {{ $question->question }}
                        </p>
                    </div>

                    {{-- Pilihan Jawaban (Radio Button) --}}
                    <div class="px-6 pb-6 space-y-3">
                        @foreach ($question->options as $key => $optionText)
                            <label class="group flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all
                                {{ old("answers.{$question->id}") === $key
                                    ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500/30'
                                    : 'border-slate-200 hover:border-blue-300 hover:bg-blue-50/50' }}
                                @error("answers.{$question->id}") border-rose-300 @enderror">
                                <input type="radio"
                                       name="answers[{{ $question->id }}]"
                                       value="{{ $key }}"
                                       {{ old("answers.{$question->id}") === $key ? 'checked' : '' }}
                                       class="mt-0.5 w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 focus:ring-2 shrink-0">
                                <div class="flex items-start gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-slate-100 group-hover:bg-blue-100 text-slate-600 group-hover:text-blue-700 font-black text-xs flex items-center justify-center shrink-0 transition">
                                        {{ $key }}
                                    </span>
                                    <span class="text-sm text-slate-700 pt-0.5 leading-relaxed">{{ $optionText }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol Submit --}}
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 p-6 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>Pastikan semua soal sudah dijawab sebelum mengirim.</span>
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold shadow-lg shadow-emerald-600/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                Kirim Jawaban & Lihat Hasil
            </button>
        </div>
    </form>
</div>

{{-- Script untuk highlight pilihan saat diklik --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Reset semua label dalam grup soal yang sama
                const name = this.getAttribute('name');
                document.querySelectorAll('input[name="' + name + '"]').forEach(function(r) {
                    const label = r.closest('label');
                    label.classList.remove('border-blue-500', 'bg-blue-50', 'ring-1', 'ring-blue-500/30');
                    label.classList.add('border-slate-200');
                });
                // Highlight pilihan aktif
                const activeLabel = this.closest('label');
                activeLabel.classList.remove('border-slate-200');
                activeLabel.classList.add('border-blue-500', 'bg-blue-50', 'ring-1', 'ring-blue-500/30');
            });
        });
    });
</script>
@endsection
