@extends('layouts.app')

@section('title', 'Kuis Cepat Energi Bersih — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider">
            🎯 Uji Pemahaman
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kuis Cepat Energi Bersih</h1>
        <p class="text-slate-600 text-base">
            Uji pemahaman Anda seputar materi transisi energi dan kumpulkan XP untuk menaikkan peringkat dan badge Anda.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            <div class="rounded-2xl bg-white border border-slate-200 p-6 flex flex-col justify-between shadow-xs hover:shadow-lg transition">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $quiz->material->category ?? 'Energi Terbarukan' }}
                        </span>
                        <span class="text-xs font-bold text-amber-600 flex items-center gap-1">
                            ⚡ +50 XP
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $quiz->title }}</h3>
                    <p class="text-xs text-slate-500">
                        Berdasarkan modul: <strong class="text-slate-700">{{ $quiz->material->title ?? '-' }}</strong>
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ $quiz->questions->count() }} Pertanyaan Pilihan Ganda
                    </p>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100">
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center block transition shadow-md shadow-blue-500/20">
                        Mulai Kerjakan Kuis
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada kuis tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection

