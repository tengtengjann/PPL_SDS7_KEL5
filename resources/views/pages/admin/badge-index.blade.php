@extends('layouts.app')

@section('title', 'Admin — Kelola Badge Pencapaian — EnergiKita')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#4E6635]">Panel Organisasi SRE</p>
            <h1 class="mt-2 text-3xl sm:text-4xl text-stone-950">Kelola Badge Pencapaian<span class="text-[#4E6635]">.</span></h1>
            <p class="text-sm text-stone-500 mt-1">Tambah, ubah, atau hapus badge pencapaian belajar EnergiKita.</p>
        </div>
        <a href="{{ route('admin.badges.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#2D3A1E] hover:bg-[#1F2915] text-white text-xs font-medium transition">
            <span>Tambah Badge Baru</span>
            <span aria-hidden="true">↗</span>
        </a>
    </div>

    {{-- Tabel Badge --}}
    <div class="bg-white rounded-[24px] border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Badge</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Nilai Target</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Peraih</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($badges as $badge)
                    <tr class="hover:bg-stone-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-[#E6EFE0] text-[#2D3A1E] flex items-center justify-center font-serif text-lg shrink-0">
                                    {{ strtoupper(substr($badge->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-stone-900">{{ $badge->name }}</p>
                                    <p class="text-xs text-stone-500 line-clamp-1 max-w-xs">{{ $badge->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full bg-stone-100 text-stone-700 text-xs font-medium">
                                {{ ucwords(str_replace('_', ' ', $badge->criteria_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-stone-900 tabular-nums">{{ $badge->criteria_value }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-stone-600 tabular-nums">{{ $badge->users()->count() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.badges.edit', $badge) }}"
                                   class="px-3.5 py-1.5 rounded-full bg-stone-100 text-stone-700 text-xs font-medium hover:bg-stone-200 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus badge &quot;{{ $badge->name }}&quot;?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3.5 py-1.5 rounded-full bg-[#FBF4F2] text-[#9A4B39] text-xs font-medium hover:bg-[#F5E4DF] transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-stone-400">
                            <p class="text-3xl mb-2 text-stone-300 font-serif">00</p>
                            <p class="font-semibold text-stone-700">Belum ada badge.</p>
                            <p class="text-xs mt-1">Klik tombol "Tambah Badge Baru" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Navigasi Kembali --}}
    <div>
        <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-900 font-medium transition">
            &larr; Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
