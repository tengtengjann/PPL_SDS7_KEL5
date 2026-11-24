@extends('layouts.app')

@section('title', 'Progres Belajar — EnergiKita')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- ============================================================
         HEADER HALAMAN
         ============================================================ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Progres Belajar Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau status penyelesaian materi pembelajaran Anda di EnergiKita.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                &larr; Beranda
            </a>
            <a href="{{ route('profile.index') }}" class="px-4 py-2 rounded-xl bg-[#E6EFE0] hover:bg-[#D5E1CA] text-[#2D3A1E] text-xs font-bold transition">
                Profil
            </a>
        </div>
    </div>

    {{-- ============================================================
         RINGKASAN PROGRES (Cards Overview)
         ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Materi --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Materi</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $totalMaterials }}</p>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#E6EFE0] text-[#2D3A1E] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Selesai</p>
                    <p class="text-2xl font-extrabold text-[#2D3A1E]">{{ $completedCount }}</p>
                </div>
            </div>
        </div>

        {{-- Belum Selesai --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-stone-100 text-stone-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Belum Selesai</p>
                    <p class="text-2xl font-extrabold text-stone-700">{{ $notCompletedCount }}</p>
                </div>
            </div>
        </div>

        {{-- Persentase --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#EDE7DD] text-stone-800 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Persentase</p>
                    <p class="text-2xl font-extrabold text-stone-900">{{ $percentage }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         PROGRESS BAR VISUAL
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-slate-700">Progress Keseluruhan</span>
            <span class="text-sm font-extrabold text-[#2D3A1E]">{{ $percentage }}%</span>
        </div>
        <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
            <div class="h-full bg-[#2D3A1E] rounded-full transition-all duration-700 shadow-sm"
                 style="width: {{ max($percentage > 0 ? 2 : 0, $percentage) }}%;"></div>
        </div>
        <p class="text-xs text-slate-400 mt-2">
            {{ $completedCount }} dari {{ $totalMaterials }} materi telah diselesaikan.
            @if($totalMaterials > 0 && $completedCount < $totalMaterials)
                Terus semangat!
            @elseif($totalMaterials > 0 && $completedCount >= $totalMaterials)
                Luar biasa! Semua materi telah diselesaikan!
            @endif
        </p>
    </div>

    {{-- ============================================================
         DAFTAR MATERI & STATUS
         ============================================================ --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <span>Daftar Materi Pembelajaran</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Status penyelesaian setiap materi berdasarkan akun Anda.</p>
        </div>

        @if($materials->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($materials as $index => $material)
                    @php
                        $progress = $userProgress->get($material->id);
                        $isCompleted = $progress && $progress->status === 'completed';
                    @endphp
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-6 py-4 hover:bg-slate-50/50 transition">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            {{-- Nomor --}}
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0
                                {{ $isCompleted ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $index + 1 }}
                            </div>

                            {{-- Info Materi --}}
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('materials.show', $material->id) }}" class="text-sm font-bold text-slate-800 hover:text-emerald-600 transition truncate block">
                                    {{ $material->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                                        {{ $material->category }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 truncate max-w-xs hidden sm:inline">{{ Str::limit($material->description, 60) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            {{-- Badge Status --}}
                            @if($isCompleted)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Not Completed
                                </span>
                            @endif

                            {{-- Tombol Tandai Selesai (hanya jika belum completed) --}}
                            @if(!$isCompleted)
                                <form method="POST" action="{{ route('learning-progress.complete', $material->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-sm"
                                            onclick="return confirm('Tandai materi ini sebagai selesai?')">
                                        ✓ Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-6">
                <p class="text-3xl mb-3 font-serif text-stone-300">00</p>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Materi</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Belum ada materi pembelajaran yang tersedia saat ini. Silakan cek kembali nanti.
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
