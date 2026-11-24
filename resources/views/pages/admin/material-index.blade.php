@extends('layouts.app')

@section('title', 'Admin — Kelola Materi Pembelajaran — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    {{-- Header Dashboard SRE (Area Inspired Typography) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 pb-6 border-b border-stone-200">
        <div class="space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-[#4E6635] block">
                Panel Organisasi SRE &bull; Fitur F-05
            </span>
            <h1 class="text-3xl sm:text-5xl font-normal font-serif text-stone-950 tracking-tight">
                Kelola Materi Pembelajaran<span class="text-[#4E6635]">.</span>
            </h1>
            <p class="text-sm text-stone-500 max-w-xl font-normal leading-relaxed">
                Tambah materi edukasi energi baru, perbarui isi konten, atau kelola soal kuis untuk mahasiswa.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('materials.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 text-xs font-medium transition shadow-xs">
                <span>Lihat Tampilan Mahasiswa</span>
                <span class="text-xs">↗</span>
            </a>
            <a href="{{ route('admin.materials.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white text-xs font-medium shadow-md shadow-[#2D3A1E]/15 transition transform hover:-translate-y-0.5">
                <span>Tambah Materi Baru</span>
                <span class="text-sm">↗</span>
            </a>
        </div>
    </div>

    {{-- Filter & Statistik Ringkas (Identik Card Style Area) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-stone-200 p-6 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-stone-400 uppercase tracking-wider font-semibold">Total Materi Aktif</p>
                <p class="text-3xl font-light font-serif text-stone-950 mt-1">{{ $materials->count() }} Modul</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#E6EFE0] text-[#2D3A1E] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2D3A1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-stone-200 p-6 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-stone-400 uppercase tracking-wider font-semibold">Soal Kuis Terhubung</p>
                <p class="text-3xl font-light font-serif text-stone-950 mt-1">
                    {{ $materials->sum(function($m) { return $m->quizzes->sum(function($q) { return $q->questions->count(); }); }) }} Soal
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EDE7DD] text-stone-800 flex items-center justify-center">
                <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3m.08 4h.01"/>
                </svg>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-stone-200 p-6 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-stone-400 uppercase tracking-wider font-semibold">Kategori SDG 7</p>
                <p class="text-3xl font-light font-serif text-stone-950 mt-1">{{ count($categories) }} Kategori</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EAE5DC] text-stone-800 flex items-center justify-center">
                <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Tabel Materi Pembelajaran --}}
    <div class="bg-white rounded-3xl border border-stone-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-serif font-normal text-stone-950">Daftar Materi Pembelajaran</h2>
                <p class="text-xs text-stone-400">Seluruh modul aktif yang terdaftar dalam kurikulum EBT EnergiKita.</p>
            </div>
            
            <form method="GET" action="{{ route('admin.materials.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul materi..."
                       class="px-4 py-2 text-xs rounded-full border border-stone-200 focus:ring-1 focus:ring-stone-400 focus:outline-none bg-stone-50/50">
                <select name="category" onchange="this.form.submit()"
                        class="px-4 py-2 text-xs rounded-full border border-stone-200 focus:ring-1 focus:ring-stone-400 focus:outline-none bg-white cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-medium rounded-full transition">
                    Cari
                </button>
                @if(!empty($search) || !empty($category))
                    <a href="{{ route('admin.materials.index') }}" class="text-xs text-rose-600 hover:underline px-1">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-stone-50/80 border-b border-stone-200 text-stone-500">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-[11px]">Materi & Kategori</th>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-[11px]">Deskripsi Singkat</th>
                        <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-[11px]">Soal Kuis</th>
                        <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-[11px]">Terakhir Diperbarui</th>
                        <th class="px-6 py-4 text-right font-bold uppercase tracking-wider text-[11px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($materials as $item)
                        @php
                            $catLower = strtolower($item->category);
                            $badgeClass = 'bg-stone-100 text-stone-800 border-stone-200';
                            if (str_contains($catLower, 'surya')) $badgeClass = 'bg-[#FFF6E5] text-[#8C6212] border-[#F4E3C1]';
                            elseif (str_contains($catLower, 'angin')) $badgeClass = 'bg-[#F0F7FA] text-[#1E5D7B] border-[#D1E6F0]';
                            elseif (str_contains($catLower, 'air')) $badgeClass = 'bg-[#EFF5FA] text-[#1D517E] border-[#CFDFEC]';
                            elseif (str_contains($catLower, 'hemat') || str_contains($catLower, 'efisiensi')) $badgeClass = 'bg-[#E6EFE0] text-[#2D3A1E] border-[#CFDEC4]';
                            elseif (str_contains($catLower, 'biomassa')) $badgeClass = 'bg-[#F2F7EC] text-[#3D5C1E] border-[#D7E8C7]';
                            elseif (str_contains($catLower, 'geotermal')) $badgeClass = 'bg-[#FAF2EC] text-[#7E451D] border-[#EDD5C5]';

                            $quiz = $item->quizzes->first();
                            $questionCount = $quiz ? $quiz->questions->count() : 0;
                        @endphp
                        <tr class="hover:bg-stone-50/70 transition group">
                            {{-- Judul & Gambar/Kategori --}}
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-stone-100 border border-stone-200 overflow-hidden shrink-0 flex items-center justify-center text-stone-400">
                                        @if($item->image && file_exists(public_path('storage/' . $item->image)))
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                        @elseif($item->image && file_exists(public_path($item->image)))
                                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-stone-100 text-stone-600 font-serif text-xs font-bold uppercase tracking-wider">
                                                {{ substr($item->category, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <a href="{{ route('materials.show', $item->id) }}" target="_blank"
                                           class="font-bold text-stone-900 group-hover:text-[#2D3A1E] transition line-clamp-1 text-sm">
                                            {{ $item->title }}
                                        </a>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                                {{ $item->category }}
                                            </span>
                                            <span class="text-[10px] text-stone-400">ID: #{{ $item->id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    {{ $item->description }}
                                </p>
                            </td>

                            {{-- Soal Kuis --}}
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.materials.questions', $item) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition {{ $questionCount > 0 ? 'bg-[#E6EFE0] text-[#2D3A1E] hover:bg-[#D5E1CA]' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3m.08 4h.01"/>
                                    </svg>
                                    <span>{{ $questionCount }} Soal</span>
                                </a>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-center text-xs text-stone-400 whitespace-nowrap">
                                {{ $item->updated_at ? $item->updated_at->format('d M Y, H:i') : '-' }}
                            </td>

                            {{-- Aksi (Pill Style) --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Kelola Kuis --}}
                                    <a href="{{ route('admin.materials.questions', $item) }}"
                                       title="Kelola Soal Kuis"
                                       class="px-3 py-1 rounded-full bg-[#EDE7DD] hover:bg-[#DCD4C7] text-stone-800 text-xs font-medium transition inline-flex items-center gap-1">
                                        <span>Soal</span>
                                        <span class="text-[10px]">↗</span>
                                    </a>

                                    {{-- Edit Materi (PBI-014) --}}
                                    <a href="{{ route('admin.materials.edit', $item) }}" 
                                       title="Edit Materi"
                                       class="px-3 py-1 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-medium transition inline-flex items-center gap-1">
                                        <span>Edit</span>
                                    </a>

                                    {{-- Hapus Materi (PBI-014) --}}
                                    <form method="POST" action="{{ route('admin.materials.destroy', $item) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi &quot;{{ addslashes($item->title) }}&quot;?\n\nMateri ini dan kuis terkait tidak akan lagi tampil pada daftar mahasiswa.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Materi"
                                                class="px-3 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-medium transition inline-flex items-center gap-1 border border-rose-200/60">
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-stone-400">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <span class="text-4xl block font-serif font-light text-stone-300">00</span>
                                    <p class="font-bold text-stone-700 text-sm">Tidak ada materi pembelajaran</p>
                                    <p class="text-xs text-stone-500">Materi belum ditambahkan atau tidak sesuai kriteria pencarian.</p>
                                    <a href="{{ route('admin.materials.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-[#2D3A1E] text-white text-xs font-medium shadow-sm hover:bg-[#1E2815] transition">
                                        <span>+ Tambah Materi Baru Sekarang</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
