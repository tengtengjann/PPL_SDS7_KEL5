@extends('layouts.app')

@section('title', 'Riwayat Kuis — EnergiKita')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- ============================================================
         HEADER HALAMAN
         ============================================================ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">📝 Riwayat & Nilai Kuis</h1>
            <p class="text-sm text-slate-500 mt-1">Evaluasi hasil kuis Anda di EnergiKita. Riwayat diurutkan dari pengerjaan terbaru.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                &larr; Beranda
            </a>
            <a href="{{ route('profile.index') }}" class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition">
                👤 Profil
            </a>
        </div>
    </div>

    {{-- ============================================================
         RINGKASAN STATISTIK KUIS
         ============================================================ --}}
    @if($quizResults->count() > 0)
        @php
            $avgScore = round($quizResults->avg('score'));
            $bestScore = $quizResults->max('score');
            $passedCount = $quizResults->where('score', '>=', 60)->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg font-bold">🎯</div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kuis</p>
                        <p class="text-2xl font-extrabold text-slate-900">{{ $quizResults->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold">📊</div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai</p>
                        <p class="text-2xl font-extrabold text-emerald-600">{{ $avgScore }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg font-bold">🏆</div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nilai Tertinggi</p>
                        <p class="text-2xl font-extrabold text-amber-600">{{ $bestScore }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg font-bold">✅</div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lulus (≥ 60)</p>
                        <p class="text-2xl font-extrabold text-indigo-600">{{ $passedCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================
         TABEL RIWAYAT KUIS
         ============================================================ --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <span>📋</span>
                <span>Daftar Riwayat Pengerjaan Kuis</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Riwayat seluruh kuis yang pernah Anda kerjakan.</p>
        </div>

        @if($quizResults->count() > 0)
            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Kuis</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Pengerjaan</th>
                            <th class="px-6 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($quizResults as $index => $result)
                            @php
                                $score = $result->score;
                                $isPassed = $score >= 60;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $result->quiz->title ?? 'Kuis Dihapus' }}</p>
                                        @if($result->quiz && $result->quiz->material)
                                            <p class="text-[11px] text-slate-400 mt-0.5">
                                                📚 {{ $result->quiz->material->title }}
                                                <span class="text-slate-300 mx-1">•</span>
                                                {{ $result->quiz->material->category }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $result->updated_at ? $result->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-extrabold {{ $score >= 80 ? 'text-emerald-600' : ($score >= 60 ? 'text-amber-600' : 'text-red-500') }}">
                                        {{ $score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($score >= 80)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Sangat Baik
                                        </span>
                                    @elseif($score >= 60)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Lulus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Belum Lulus
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="sm:hidden divide-y divide-slate-100">
                @foreach($quizResults as $index => $result)
                    @php
                        $score = $result->score;
                    @endphp
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800">{{ $index + 1 }}. {{ $result->quiz->title ?? 'Kuis Dihapus' }}</p>
                                @if($result->quiz && $result->quiz->material)
                                    <p class="text-[11px] text-slate-400 mt-0.5">📚 {{ $result->quiz->material->category }}</p>
                                @endif
                            </div>
                            <span class="text-xl font-extrabold {{ $score >= 80 ? 'text-emerald-600' : ($score >= 60 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ $score }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">
                                {{ $result->updated_at ? $result->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                            @if($score >= 80)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Sangat Baik</span>
                            @elseif($score >= 60)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Lulus</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700">Belum Lulus</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-6">
                <p class="text-5xl mb-4">📭</p>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Riwayat Kuis</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Anda belum pernah mengerjakan kuis. Mulai kerjakan kuis untuk mengevaluasi pemahaman Anda!
                </p>
                <a href="{{ route('quiz.index') }}"
                   class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md transition">
                    🎯 Mulai Kuis Sekarang
                </a>
            </div>
        @endif
    </div>

    {{-- Navigasi Bawah --}}
    <div class="flex flex-wrap gap-3 justify-center">
        <a href="{{ route('learning-progress.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-bold border border-blue-200 transition">
            📊 Progres Belajar
        </a>
        <a href="{{ route('quiz.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md">
            🎯 Kerjakan Kuis Baru
        </a>
    </div>

</div>
@endsection
