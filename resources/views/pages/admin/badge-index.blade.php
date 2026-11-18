@extends('layouts.app')

@section('title', 'Admin — Kelola Badge Pencapaian — EnergiKita')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">🛡️ Kelola Badge Pencapaian</h1>
            <p class="text-sm text-slate-500 mt-1">Tambah, edit, atau hapus badge pencapaian belajar EnergiKita.</p>
        </div>
        <a href="{{ route('admin.badges.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md transition transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Badge Baru
        </a>
    </div>

    {{-- Tabel Badge --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Badge</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($badges as $badge)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $badge->icon }}</span>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $badge->name }}</p>
                                    <p class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ $badge->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200">
                                {{ ucwords(str_replace('_', ' ', $badge->criteria_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-slate-900">{{ $badge->criteria_value }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-slate-600">{{ $badge->users()->count() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.badges.edit', $badge) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold hover:bg-amber-100 border border-amber-200 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}" 
                                      onsubmit="return confirm('Yakin ingin menghapus badge &quot;{{ $badge->name }}&quot;?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold hover:bg-rose-100 border border-rose-200 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <p class="text-4xl mb-3">📭</p>
                            <p class="font-bold">Belum ada badge.</p>
                            <p class="text-xs mt-1">Klik tombol "Tambah Badge Baru" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Navigasi Kembali --}}
    <div class="mt-8">
        <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-emerald-600 font-semibold transition">
            &larr; Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
