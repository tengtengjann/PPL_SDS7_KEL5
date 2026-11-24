@extends('layouts.app')

@section('title', 'Hasil Kuis: ' . $quiz->title . ' — EnergiKita')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header Hasil --}}
    <div class="mb-10">
        <a href="{{ route('quiz.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Kembali ke Daftar Kuis
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Hasil Kuis</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $quiz->title }}</p>
    </div>

    {{-- Kartu Skor Utama --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden mb-10">
        <div class="p-8 sm:p-10 text-center bg-gradient-to-br
            {{ $score >= 80 ? 'from-emerald-50 via-teal-50 to-green-50' : ($score >= 60 ? 'from-amber-50 via-yellow-50 to-orange-50' : 'from-rose-50 via-red-50 to-pink-50') }}">

            {{-- Ikon Hasil --}}
            <div class="mb-5">
                @if($score >= 80)
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    </div>
                @elseif($score >= 60)
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 text-amber-600 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    </div>
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-100 text-rose-600 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Skor --}}
            <div class="mb-2">
                <span class="text-6xl sm:text-7xl font-black
                    {{ $score >= 80 ? 'text-emerald-600' : ($score >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                    {{ $score }}
                </span>
                <span class="text-2xl font-bold text-slate-400">/100</span>
            </div>

            {{-- Pesan Motivasi --}}
            <p class="text-lg font-bold mt-2
                {{ $score >= 80 ? 'text-emerald-700' : ($score >= 60 ? 'text-amber-700' : 'text-rose-700') }}">
                @if($score >= 80)
                    Luar Biasa! Pemahaman Anda sangat baik!
                @elseif($score >= 60)
                    Cukup Baik! Terus pelajari materi untuk hasil yang lebih baik.
                @else
                    Jangan Menyerah! Pelajari pembahasan di bawah dan coba lagi.
                @endif
            </p>

            @if(isset($xpEarned) && $xpEarned > 0)
                <div class="mt-4 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#E6EFE0] text-[#2D3A1E] border border-[#CFDEC4] text-sm font-black shadow-xs">
                    <span>Selamat! Anda memperoleh +{{ $xpEarned }} XP ke akun Anda!</span>
                </div>
            @endif
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-3 divide-x divide-slate-100 border-t border-slate-100">
            <div class="p-5 text-center">
                <p class="text-2xl font-black text-slate-900">{{ $totalQuestions }}</p>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Total Soal</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-black text-emerald-600">{{ $correctCount }}</p>
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider mt-1">Benar</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-black text-rose-600">{{ $wrongCount }}</p>
                <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider mt-1">Salah</p>
            </div>
        </div>
    </div>

    {{-- Judul Pembahasan --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Review & Pembahasan</h2>
            <p class="text-sm text-slate-500">Pelajari penjelasan untuk setiap soal yang telah dikerjakan.</p>
        </div>
    </div>

    {{-- Daftar Soal + Pembahasan --}}
    <div class="space-y-6">
        @foreach ($results as $index => $result)
            @php
                $question = $result['question'];
                $userAnswer = $result['user_answer'];
                $isCorrect = $result['is_correct'];
            @endphp

            <div class="bg-white rounded-2xl border-2 overflow-hidden shadow-xs
                {{ $isCorrect ? 'border-emerald-200' : 'border-rose-200' }}">

                {{-- Header Soal --}}
                <div class="px-6 py-4 flex items-center justify-between
                    {{ $isCorrect ? 'bg-emerald-50 border-b border-emerald-100' : 'bg-rose-50 border-b border-rose-100' }}">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl font-black text-sm flex items-center justify-center shadow-sm
                            {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                            {{ $index + 1 }}
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-wider
                            {{ $isCorrect ? 'text-emerald-600' : 'text-rose-600' }}">
                            Soal {{ $index + 1 }}
                        </span>
                    </div>
                    {{-- Badge Benar/Salah --}}
                    @if($isCorrect)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Benar
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Salah
                        </span>
                    @endif
                </div>

                {{-- Teks Soal --}}
                <div class="px-6 pt-5 pb-3">
                    <p class="text-base font-semibold text-slate-800 leading-relaxed">{{ $question->question }}</p>
                </div>

                {{-- Pilihan Jawaban dengan Indikator Warna --}}
                <div class="px-6 pb-4 space-y-2.5">
                    @foreach ($question->options as $key => $optionText)
                        @php
                            $isUserChoice   = strtoupper($userAnswer) === strtoupper($key);
                            $isCorrectOption = strtoupper($question->answer) === strtoupper($key);
                        @endphp

                        <div class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition
                            @if($isCorrectOption)
                                border-emerald-400 bg-emerald-50
                            @elseif($isUserChoice && !$isCorrect)
                                border-rose-400 bg-rose-50
                            @else
                                border-slate-100 bg-slate-50/50
                            @endif
                        ">
                            {{-- Ikon Status --}}
                            <div class="shrink-0 mt-0.5">
                                @if($isCorrectOption)
                                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @elseif($isUserChoice && !$isCorrect)
                                    <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                @else
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300"></div>
                                @endif
                            </div>

                            <div class="flex items-start gap-2 flex-1">
                                <span class="w-7 h-7 rounded-lg font-black text-xs flex items-center justify-center shrink-0
                                    @if($isCorrectOption)
                                        bg-emerald-200 text-emerald-800
                                    @elseif($isUserChoice && !$isCorrect)
                                        bg-rose-200 text-rose-800
                                    @else
                                        bg-slate-200 text-slate-500
                                    @endif
                                ">
                                    {{ $key }}
                                </span>
                                <span class="text-sm pt-0.5 leading-relaxed
                                    @if($isCorrectOption)
                                        text-emerald-800 font-semibold
                                    @elseif($isUserChoice && !$isCorrect)
                                        text-rose-800 line-through
                                    @else
                                        text-slate-600
                                    @endif
                                ">
                                    {{ $optionText }}
                                </span>
                            </div>

                            {{-- Label --}}
                            <div class="shrink-0">
                                @if($isCorrectOption && $isUserChoice)
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Jawaban Anda ✓</span>
                                @elseif($isCorrectOption)
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Kunci Jawaban</span>
                                @elseif($isUserChoice && !$isCorrect)
                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Jawaban Anda ✗</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pembahasan / Penjelasan --}}
                @if($question->explanation)
                    <div class="mx-6 mb-6 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                        <div class="flex items-start gap-2.5">
                            <div class="shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-1">Pembahasan</p>
                                <p class="text-sm text-indigo-900 leading-relaxed">{{ $question->explanation }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Tombol Aksi Bawah --}}
    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('quiz.show', $quiz) }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold shadow-lg shadow-blue-600/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M2.985 19.644l3.181-3.182"/></svg>
            Ulangi Kuis Ini
        </a>
        <a href="{{ route('quiz.index') }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-white border-2 border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 text-slate-700 text-sm font-bold shadow-xs transition-all transform hover:-translate-y-0.5 active:translate-y-0 w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            Kembali ke Daftar Kuis
        </a>
    </div>
</div>
@endsection
