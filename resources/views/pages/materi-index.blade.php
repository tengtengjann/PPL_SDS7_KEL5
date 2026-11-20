@extends('layouts.app')

@section('title', 'Belajar & Modul Materi — EnergiKita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <!-- Header Modul EBT -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
            📚 Kurikulum EBT &bull; Modul F-06
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Modul Materi Energi Bersih</h1>
        <p class="text-slate-600 text-base leading-relaxed">
            Eksplorasi konsep fundamental, pemanfaatan teknologi, dan inovasi transisi energi terbarukan di Indonesia sesuai prinsip SDG 7.
        </p>
    </div>

    <!-- Panel Kontrol: Search Bar, Kategori Pills, dan Sort Dropdown -->
    <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-5">
        <!-- Form Pencarian & Pengurutan -->
        <form id="filter-form" method="GET" action="{{ route('materials.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <!-- PBI-016: Search Bar -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       id="search-input"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Cari materi berdasarkan judul atau topik (misal: fotovoltaik, turbin, mikrohidro)..."
                       class="w-full pl-10 pr-10 py-2.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition"
                       autocomplete="off">
                <button type="button"
                        id="search-clear-btn"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition {{ empty($search) ? 'hidden' : '' }}"
                        title="Hapus pencarian">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- PBI-018: Dropdown Pengurutan (Sort) -->
            <div class="flex items-center gap-2 shrink-0">
                <label for="sort-select" class="text-xs font-bold text-slate-500 whitespace-nowrap hidden sm:inline">
                    Urutkan:
                </label>
                <div class="relative w-full md:w-auto">
                    <select id="sort-select"
                            name="sort"
                            class="w-full md:w-auto appearance-none pl-3.5 pr-9 py-2.5 rounded-2xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition cursor-pointer">
                        <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>✨ Terbaru</option>
                        <option value="terlama" {{ ($sort ?? '') === 'terlama' ? 'selected' : '' }}>⏳ Terlama</option>
                        <option value="judul-az" {{ ($sort ?? '') === 'judul-az' ? 'selected' : '' }}>🔤 Judul A - Z</option>
                        <option value="judul-za" {{ ($sort ?? '') === 'judul-za' ? 'selected' : '' }}>🔤 Judul Z - A</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Hidden input kategori untuk sinkronisasi form submit jika JS dinonaktifkan -->
            <input type="hidden" name="category" id="hidden-category-input" value="{{ $category ?? '' }}">
        </form>

        <!-- PBI-017: Filter Kategori Energi (Tab / Pill) -->
        <div class="pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Energi:</span>
                <button type="button" id="reset-all-filters-btn" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline {{ empty($search) && empty($category) ? 'hidden' : '' }}">
                    Bersihkan Filter
                </button>
            </div>
            
            <div id="category-pills" class="flex flex-wrap items-center gap-2">
                @php
                    $activeCategory = strtolower(trim($category ?? ''));
                @endphp
                <button type="button"
                        data-category=""
                        class="category-pill px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($activeCategory) || $activeCategory === 'semua' ? 'bg-emerald-600 text-white shadow-sm ring-2 ring-emerald-600 ring-offset-1' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                    @php
                        $catLower = strtolower($cat);
                        $isActive = ($activeCategory === $catLower) || 
                                    (str_replace('-', ' ', $activeCategory) === $catLower) ||
                                    (!empty($activeCategory) && str_contains($catLower, $activeCategory));
                        
                        // Ikon kategori
                        $icon = '📚';
                        if (str_contains($catLower, 'surya')) $icon = '☀️';
                        elseif (str_contains($catLower, 'angin')) $icon = '💨';
                        elseif (str_contains($catLower, 'air')) $icon = '💧';
                        elseif (str_contains($catLower, 'hemat') || str_contains($catLower, 'efisiensi')) $icon = '⚡';
                        elseif (str_contains($catLower, 'biomassa')) $icon = '🌱';
                        elseif (str_contains($catLower, 'geotermal')) $icon = '🌋';
                    @endphp
                    <button type="button"
                            data-category="{{ $cat }}"
                            class="category-pill px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $isActive ? 'bg-emerald-600 text-white shadow-sm ring-2 ring-emerald-600 ring-offset-1' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <span>{{ $icon }}</span>
                        <span>{{ $cat }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Indikator Jumlah Hasil & Filter Aktif -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-1 text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <span>Menampilkan</span>
            <span id="results-count" class="font-extrabold text-slate-800 bg-slate-200 px-2 py-0.5 rounded-md">
                {{ $materials->count() }}
            </span>
            <span>modul materi</span>
            <!-- Loading indicator spinner -->
            <span id="loading-indicator" class="hidden items-center gap-1.5 text-emerald-600 font-bold ml-2">
                <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memuat...</span>
            </span>
        </div>

        <div id="active-filter-chips" class="flex flex-wrap items-center gap-1.5">
            @if(!empty($search))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                    Kata kunci: "<strong>{{ $search }}</strong>"
                </span>
            @endif
            @if(!empty($category) && strtolower($category) !== 'semua')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                    Kategori: <strong>{{ $category }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Grid Materi & Empty State -->
    <div id="materials-container">
        <!-- Grid Kartu Materi -->
        <div id="materials-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ $materials->isEmpty() ? 'hidden' : '' }}">
            @foreach($materials as $item)
                @php
                    $badgeClass = 'bg-slate-100 text-slate-800';
                    $catLower = strtolower($item->category);
                    if (str_contains($catLower, 'surya')) $badgeClass = 'bg-amber-100 text-amber-800';
                    elseif (str_contains($catLower, 'angin')) $badgeClass = 'bg-sky-100 text-sky-800';
                    elseif (str_contains($catLower, 'air')) $badgeClass = 'bg-blue-100 text-blue-800';
                    elseif (str_contains($catLower, 'hemat') || str_contains($catLower, 'efisiensi')) $badgeClass = 'bg-emerald-100 text-emerald-800';
                    elseif (str_contains($catLower, 'biomassa')) $badgeClass = 'bg-lime-100 text-lime-800';
                    elseif (str_contains($catLower, 'geotermal')) $badgeClass = 'bg-orange-100 text-orange-800';
                @endphp
                <div class="material-card rounded-3xl bg-white border border-slate-200 p-6 flex flex-col justify-between shadow-xs hover:shadow-lg transition-all duration-200 group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold {{ $badgeClass }}">
                                {{ $item->category }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">SDG 7 Modul</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                            {{ $item->title }}
                        </h3>
                        <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $item->description }}
                        </p>
                    </div>
                    <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-medium">10 Menit Baca</span>
                        <a href="{{ route('materials.show', $item->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1.5 transition">
                            <span>Buka Materi</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PBI-016: Empty State saat materi tidak ditemukan -->
        <div id="empty-state" class="col-span-full text-center py-16 px-4 bg-white rounded-3xl border border-dashed border-slate-300 shadow-xs {{ $materials->isEmpty() ? '' : 'hidden' }}">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900 mb-1.5">Materi tidak ditemukan</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
                Tidak ada materi yang sesuai dengan kata kunci atau kriteria filter yang Anda tentukan. Silakan coba kata kunci lain atau bersihkan pilihan filter.
            </p>
            <button type="button" id="reset-empty-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Reset Pencarian & Filter</span>
            </button>
        </div>
    </div>
</div>

<!-- Script Integrasi Frontend API, Debounce, Filter Kategori & Pengurutan (Modul F-06) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const searchClearBtn = document.getElementById('search-clear-btn');
    const sortSelect = document.getElementById('sort-select');
    const hiddenCategoryInput = document.getElementById('hidden-category-input');
    const categoryPillsContainer = document.getElementById('category-pills');
    const materialsGrid = document.getElementById('materials-grid');
    const emptyState = document.getElementById('empty-state');
    const resultsCount = document.getElementById('results-count');
    const loadingIndicator = document.getElementById('loading-indicator');
    const activeFilterChips = document.getElementById('active-filter-chips');
    const resetAllFiltersBtn = document.getElementById('reset-all-filters-btn');
    const resetEmptyBtn = document.getElementById('reset-empty-btn');

    let currentCategory = hiddenCategoryInput.value || '';
    let currentSearch = searchInput.value || '';
    let currentSort = sortSelect.value || 'terbaru';
    let abortController = null;

    /**
     * Helper Debounce (300ms) untuk optimasi request pencarian (PBI-016).
     */
    function debounce(callback, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => callback.apply(this, args), delay);
        };
    }

    /**
     * Kembalikan kelas badge warna berdasarkan nama kategori energi.
     */
    function getCategoryBadgeClass(category) {
        const cat = (category || '').toLowerCase();
        if (cat.includes('surya')) return 'bg-amber-100 text-amber-800';
        if (cat.includes('angin')) return 'bg-sky-100 text-sky-800';
        if (cat.includes('air')) return 'bg-blue-100 text-blue-800';
        if (cat.includes('hemat') || cat.includes('efisiensi')) return 'bg-emerald-100 text-emerald-800';
        if (cat.includes('biomassa')) return 'bg-lime-100 text-lime-800';
        if (cat.includes('geotermal')) return 'bg-orange-100 text-orange-800';
        return 'bg-slate-100 text-slate-800';
    }

    /**
     * Render kartu materi ke dalam DOM secara aman.
     */
    function renderMaterials(materials) {
        if (!materials || materials.length === 0) {
            materialsGrid.innerHTML = '';
            materialsGrid.classList.add('hidden');
            emptyState.classList.remove('hidden');
            resultsCount.textContent = '0';
            return;
        }

        emptyState.classList.add('hidden');
        materialsGrid.classList.remove('hidden');
        resultsCount.textContent = materials.length;

        const cardsHtml = materials.map(item => {
            const badgeClass = getCategoryBadgeClass(item.category);
            const safeTitle = escapeHtml(item.title);
            const safeDesc = escapeHtml(item.description);
            const safeCat = escapeHtml(item.category);
            const detailUrl = item.detail_url || ('/materials/' + item.id);

            return `
                <div class="material-card rounded-3xl bg-white border border-slate-200 p-6 flex flex-col justify-between shadow-xs hover:shadow-lg transition-all duration-200 group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold ${badgeClass}">
                                ${safeCat}
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">SDG 7 Modul</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                            ${safeTitle}
                        </h3>
                        <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                            ${safeDesc}
                        </p>
                    </div>
                    <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-medium">10 Menit Baca</span>
                        <a href="${detailUrl}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1.5 transition">
                            <span>Buka Materi</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    </div>
                </div>
            `;
        }).join('');

        materialsGrid.innerHTML = cardsHtml;
    }

    /**
     * Escape string untuk mencegah XSS injection.
     */
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Perbarui chips filter aktif di UI.
     */
    function updateActiveChips() {
        let chips = '';
        if (currentSearch) {
            chips += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                    Kata kunci: "<strong>${escapeHtml(currentSearch)}</strong>"
                </span>
            `;
        }
        if (currentCategory && currentCategory.toLowerCase() !== 'semua') {
            chips += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                    Kategori: <strong>${escapeHtml(currentCategory)}</strong>
                </span>
            `;
        }
        activeFilterChips.innerHTML = chips;

        // Tampilkan/sembunyikan tombol bersihkan filter
        if (currentSearch || (currentCategory && currentCategory.toLowerCase() !== 'semua')) {
            resetAllFiltersBtn.classList.remove('hidden');
        } else {
            resetAllFiltersBtn.classList.add('hidden');
        }

        // Tampilkan/sembunyikan tombol clear search
        if (currentSearch) {
            searchClearBtn.classList.remove('hidden');
        } else {
            searchClearBtn.classList.add('hidden');
        }
    }

    /**
     * Perbarui style tombol pill kategori aktif.
     */
    function updateCategoryPillsUI() {
        const pills = categoryPillsContainer.querySelectorAll('.category-pill');
        const activeCatLower = (currentCategory || '').toLowerCase();

        pills.forEach(pill => {
            const pillCat = (pill.getAttribute('data-category') || '').toLowerCase();
            let isMatch = false;

            if (!activeCatLower || activeCatLower === 'semua') {
                isMatch = (!pillCat || pillCat === 'semua');
            } else {
                isMatch = (pillCat === activeCatLower) || 
                          (pillCat.replace('-', ' ') === activeCatLower) ||
                          (pillCat !== '' && (pillCat.includes(activeCatLower) || activeCatLower.includes(pillCat)));
            }

            if (isMatch) {
                pill.className = 'category-pill px-4 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm ring-2 ring-emerald-600 ring-offset-1';
            } else {
                pill.className = 'category-pill px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200';
            }
        });
    }

    /**
     * Fetch data materi dari API backend dengan parameter search, category, sort.
     */
    async function fetchMaterials() {
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        loadingIndicator.classList.remove('hidden');
        loadingIndicator.classList.add('inline-flex');

        const params = new URLSearchParams();
        if (currentSearch) params.set('search', currentSearch);
        if (currentCategory && currentCategory.toLowerCase() !== 'semua') params.set('category', currentCategory);
        if (currentSort) params.set('sort', currentSort);

        // Update URL di browser tanpa reload halaman (deep link support)
        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.replaceState({}, '', newUrl);

        try {
            const response = await fetch(`{{ route('materials.index') }}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: abortController.signal
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data materi.');
            }

            const result = await response.json();
            renderMaterials(result.data);
            updateActiveChips();
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Fetch error:', error);
            }
        } finally {
            loadingIndicator.classList.add('hidden');
            loadingIndicator.classList.remove('inline-flex');
        }
    }

    // ========================================================
    // Event Listeners
    // ========================================================

    // 1. PBI-016: Input pencarian dengan Debounce (300ms)
    const debouncedSearch = debounce(function () {
        currentSearch = searchInput.value.trim();
        fetchMaterials();
    }, 300);

    searchInput.addEventListener('input', debouncedSearch);

    // Tombol bersihkan input pencarian
    searchClearBtn.addEventListener('click', function () {
        searchInput.value = '';
        currentSearch = '';
        fetchMaterials();
        searchInput.focus();
    });

    // 2. PBI-017: Filter Kategori Energi (Klik Pill)
    categoryPillsContainer.addEventListener('click', function (e) {
        const pill = e.target.closest('.category-pill');
        if (!pill) return;

        const selectedCategory = pill.getAttribute('data-category') || '';
        currentCategory = selectedCategory;
        hiddenCategoryInput.value = currentCategory;

        updateCategoryPillsUI();
        fetchMaterials();
    });

    // 3. PBI-018: Dropdown Pengurutan (Sort Change)
    sortSelect.addEventListener('change', function () {
        currentSort = sortSelect.value;
        fetchMaterials();
    });

    // Reset Semua Filter
    function resetAllFilters() {
        searchInput.value = '';
        currentSearch = '';
        currentCategory = '';
        hiddenCategoryInput.value = '';
        sortSelect.value = 'terbaru';
        currentSort = 'terbaru';

        updateCategoryPillsUI();
        fetchMaterials();
    }

    resetAllFiltersBtn.addEventListener('click', resetAllFilters);
    resetEmptyBtn.addEventListener('click', resetAllFilters);

    // Mencegah form reload default saat enter ditekan di input search
    document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        currentSearch = searchInput.value.trim();
        fetchMaterials();
    });
});
</script>
@endsection
