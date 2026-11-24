@extends('layouts.app')

@section('title', ($isEditing ? 'Edit Badge' : 'Tambah Badge Baru') . ' — Admin EnergiKita')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.badges.index') }}" class="text-sm text-slate-500 hover:text-emerald-600 font-semibold transition">
            &larr; Kembali ke Daftar Badge
        </a>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-4">
            {{ $isEditing ? 'Edit Badge' : 'Tambah Badge Baru' }}
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            {{ $isEditing ? 'Perbarui informasi badge pencapaian.' : 'Buat badge pencapaian baru untuk para pelajar EnergiKita.' }}
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
        <form method="POST" 
              action="{{ $isEditing ? route('admin.badges.update', $badge) : route('admin.badges.store') }}"
              class="space-y-6">
            @csrf
            @if($isEditing)
                @method('PUT')
            @endif

            {{-- Nama Badge --}}
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Badge <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $badge->name ?? '') }}"
                       placeholder="Contoh: Quiz Champion"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          placeholder="Jelaskan secara singkat tentang badge ini..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400 resize-none">{{ old('description', $badge->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ikon --}}
            <div>
                <label for="icon" class="block text-sm font-bold text-slate-700 mb-1.5">Ikon (Emoji) <span class="text-rose-500">*</span></label>
                <input type="text" name="icon" id="icon" required
                       value="{{ old('icon', $badge->icon ?? 'badge') }}"
                       placeholder="badge"
                       class="w-24 px-4 py-3 rounded-xl border border-slate-300 text-center text-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <p class="mt-1 text-xs text-slate-500">Kode ikon opsional (disimpan sebagai referensi). Tampilan badge memakai inisial nama.</p>
                @error('icon')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipe Kriteria --}}
            <div>
                <label for="criteria_type" class="block text-sm font-bold text-slate-700 mb-1.5">Tipe Kriteria <span class="text-rose-500">*</span></label>
                <select name="criteria_type" id="criteria_type" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                    <option value="">— Pilih Tipe Kriteria —</option>
                    <option value="modules_completed" {{ old('criteria_type', $badge->criteria_type ?? '') === 'modules_completed' ? 'selected' : '' }}>
                        Modul Selesai (modules_completed)
                    </option>
                    <option value="quizzes_passed" {{ old('criteria_type', $badge->criteria_type ?? '') === 'quizzes_passed' ? 'selected' : '' }}>
                        Kuis Dilalui (quizzes_passed)
                    </option>
                    <option value="xp_reached" {{ old('criteria_type', $badge->criteria_type ?? '') === 'xp_reached' ? 'selected' : '' }}>
                        XP Dicapai (xp_reached)
                    </option>
                    <option value="missions_completed" {{ old('criteria_type', $badge->criteria_type ?? '') === 'missions_completed' ? 'selected' : '' }}>
                        Misi Selesai (missions_completed)
                    </option>
                </select>
                @error('criteria_type')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nilai Kriteria --}}
            <div>
                <label for="criteria_value" class="block text-sm font-bold text-slate-700 mb-1.5">Nilai Ambang Batas <span class="text-rose-500">*</span></label>
                <input type="number" name="criteria_value" id="criteria_value" required min="1"
                       value="{{ old('criteria_value', $badge->criteria_value ?? '') }}"
                       placeholder="Contoh: 5"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400">
                <p class="mt-1 text-xs text-slate-500">Jumlah minimum yang harus dicapai user untuk mendapatkan badge ini.</p>
                @error('criteria_value')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview --}}
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Preview Badge</p>
                <div class="flex items-center gap-3">
                    <span class="text-3xl" id="preview-icon">{{ strtoupper(substr($badge->name ?? 'B', 0, 1)) }}</span>
                    <div>
                        <p class="font-bold text-slate-900" id="preview-name">{{ $badge->name ?? 'Nama Badge' }}</p>
                        <p class="text-xs text-slate-500" id="preview-desc">{{ $badge->description ?? 'Deskripsi badge...' }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition transform hover:-translate-y-0.5">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambahkan Badge' }}
                </button>
                <a href="{{ route('admin.badges.index') }}" 
                   class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>

{{-- Live Preview Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const iconInput = document.getElementById('icon');
        const descInput = document.getElementById('description');
        const previewName = document.getElementById('preview-name');
        const previewIcon = document.getElementById('preview-icon');
        const previewDesc = document.getElementById('preview-desc');

        if (nameInput && previewName) {
            nameInput.addEventListener('input', () => { previewName.textContent = nameInput.value || 'Nama Badge'; if (previewIcon) previewIcon.textContent = (nameInput.value || 'B').charAt(0).toUpperCase(); });
        }
        if (iconInput && previewIcon) {
            iconInput.addEventListener('input', () => void 0);
        }
        if (descInput && previewDesc) {
            descInput.addEventListener('input', () => previewDesc.textContent = descInput.value || 'Deskripsi badge...');
        }
    });
</script>
@endsection
