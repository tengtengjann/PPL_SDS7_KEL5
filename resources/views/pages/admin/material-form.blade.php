@extends('layouts.app')

@section('title', ($isEditing ? 'Edit Materi' : 'Tambah Materi Baru') . ' — Admin Organisasi SRE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- Breadcrumb & Header (Area Editorial Style) --}}
    <div class="space-y-2">
        <a href="{{ route('admin.materials.index') }}" class="inline-flex items-center gap-1.5 text-xs text-stone-500 hover:text-stone-900 font-medium transition">
            <span>&larr;</span>
            <span>Kembali ke Daftar Materi</span>
        </a>
        <div class="pt-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-[#4E6635] block">
                Panel Organisasi SRE &bull; Modul F-05
            </span>
            <h1 class="text-3xl sm:text-5xl font-normal font-serif text-stone-950 tracking-tight mt-1">
                {{ $isEditing ? 'Edit Materi Pembelajaran.' : 'Tambah Materi Pembelajaran Baru.' }}
            </h1>
            <p class="text-sm text-stone-500 mt-2 font-normal leading-relaxed">
                {{ $isEditing ? 'Perbarui informasi modul, teks materi, atau gambar pendukung kurikulum SDG 7.' : 'Lengkapi formulir berikut untuk menerbitkan materi edukasi energi terbarukan baru bagi Mahasiswa.' }}
            </p>
        </div>
    </div>

    {{-- Kartu Formulir --}}
    <div class="bg-white rounded-3xl border border-stone-200 shadow-xs p-6 sm:p-10">
        <form method="POST" 
              action="{{ $isEditing ? route('admin.materials.update', $material) : route('admin.materials.store') }}"
              enctype="multipart/form-data"
              class="space-y-7">
            @csrf
            @if($isEditing)
                @method('PUT')
            @endif

            {{-- 1. Judul Materi --}}
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Judul Materi Pembelajaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" required
                       value="{{ old('title', $material->title ?? '') }}"
                       placeholder="Contoh: Prinsip Kerja Turbin Angin Lepas Pantai (Offshore Wind)"
                       class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 @error('title') border-rose-400 bg-rose-50/30 @enderror">
                @error('title')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Kategori Energi --}}
            <div class="space-y-1.5">
                <label for="category" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Kategori Energi (SDG 7) <span class="text-rose-500">*</span>
                </label>
                <select name="category" id="category" required
                        class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition cursor-pointer @error('category') border-rose-400 bg-rose-50/30 @enderror">
                    <option value="">— Pilih Kategori Energi —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $material->category ?? '') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-stone-400">Pilih salah satu pilar energi terbarukan atau konservasi daya.</p>
                @error('category')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- 3. Deskripsi Singkat --}}
            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Deskripsi Singkat (Ringkasan) <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required
                          placeholder="Jelaskan secara singkat intisari materi ini agar menarik minat mahasiswa..."
                          class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 resize-none leading-relaxed @error('description') border-rose-400 bg-rose-50/30 @enderror">{{ old('description', $material->description ?? '') }}</textarea>
                <p class="text-[11px] text-stone-400">Maksimal 1000 karakter. Ditampilkan pada katalog materi mahasiswa.</p>
                @error('description')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- 4. Konten / Teks Materi Lengkap --}}
            <div class="space-y-1.5">
                <label for="body" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Isi Konten Materi Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" id="body" rows="9" required
                          placeholder="Tuliskan materi pembelajaran lengkap di sini, meliputi konsep dasar, cara kerja teknis, hingga potensi implementasinya..."
                          class="w-full px-4 py-3.5 rounded-2xl border border-stone-200 bg-stone-50/40 text-sm text-stone-900 focus:bg-white focus:ring-1 focus:ring-stone-400 focus:outline-none transition placeholder-stone-400 leading-relaxed @error('body') border-rose-400 bg-rose-50/30 @enderror">{{ old('body', $material->body ?? '') }}</textarea>
                @error('body')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- 5. Unggah Gambar Materi --}}
            <div class="space-y-2">
                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Gambar Ilustrasi Materi
                </label>
                
                @if($isEditing && !empty($material->image))
                    <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-white border border-stone-200 overflow-hidden shrink-0 flex items-center justify-center">
                            @if(file_exists(public_path('storage/' . $material->image)))
                                <img src="{{ asset('storage/' . $material->image) }}" alt="Preview" class="w-full h-full object-cover">
                            @elseif(file_exists(public_path($material->image)))
                                <img src="{{ asset($material->image) }}" alt="Preview" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-stone-400">Gambar ada</span>
                            @endif
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-stone-800">Gambar saat ini:</p>
                            <p class="text-stone-500 truncate max-w-xs">{{ $material->image }}</p>
                            <p class="text-stone-400 text-[11px] mt-0.5">Pilih file baru di bawah jika ingin menggantinya.</p>
                        </div>
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
                       class="w-full px-4 py-2.5 rounded-2xl border border-stone-200 text-xs text-stone-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#E6EFE0] file:text-[#2D3A1E] hover:file:bg-[#D5E1CA] transition cursor-pointer @error('image') border-rose-400 bg-rose-50/30 @enderror">
                <p class="text-[11px] text-stone-400">Format: JPEG, PNG, JPG, WEBP, SVG. Ukuran maksimal: 2MB.</p>
                @error('image')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi Pill --}}
            <div class="pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.materials.index') }}" 
                   class="px-6 py-2.5 rounded-full border border-stone-300 text-stone-600 hover:bg-stone-50 text-xs font-medium transition">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#2D3A1E] hover:bg-[#1E2815] text-white text-xs sm:text-sm font-medium shadow-md shadow-[#2D3A1E]/15 transition transform hover:-translate-y-0.5">
                    <span>{{ $isEditing ? 'Perbarui Materi' : 'Simpan & Publikasikan Materi' }}</span>
                    <span class="text-sm">↗</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
