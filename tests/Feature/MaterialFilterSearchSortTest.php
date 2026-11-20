<?php

namespace Tests\Feature;

use App\Models\Material;
use Database\Seeders\EnergiKitaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialFilterSearchSortTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EnergiKitaSeeder::class);
    }

    // =========================================================================
    // PBI-016: Mencari Materi Menggunakan Kata Kunci
    // =========================================================================

    /**
     * PBI-016: Halaman daftar materi memuat komponen input search bar dan daftar materi default.
     */
    public function test_pbi_016_materi_index_renders_search_bar_and_default_materials(): void
    {
        $response = $this->get(route('materials.index'));

        $response->assertStatus(200);
        $response->assertSee('id="search-input"', false);
        $response->assertSee('placeholder="Cari materi', false);
        $response->assertSee('Modul Materi Energi Bersih');
        $response->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
    }

    /**
     * PBI-016: Pencarian berdasarkan kata kunci pada judul materi (case-insensitive).
     */
    public function test_pbi_016_search_by_title_case_insensitive(): void
    {
        // Lowercase query
        $resLower = $this->get(route('materials.index', ['search' => 'fotovoltaik']));
        $resLower->assertStatus(200);
        $resLower->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
        $resLower->assertDontSee('Prinsip Kerja Turbin Angin Modern');

        // UPPERCASE query
        $resUpper = $this->get(route('materials.index', ['search' => 'FOTOVOLTAIK']));
        $resUpper->assertStatus(200);
        $resUpper->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
        $resUpper->assertDontSee('Prinsip Kerja Turbin Angin Modern');

        // Mixed-case query
        $resMixed = $this->get(route('materials.index', ['search' => 'FoToVolTaIk']));
        $resMixed->assertStatus(200);
        $resMixed->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
    }

    /**
     * PBI-016: Pencarian berdasarkan kata kunci pada deskripsi atau isi/body materi.
     */
    public function test_pbi_016_search_matches_description_and_body(): void
    {
        // 'aerodinamis' ada di dalam isi/body materi turbin angin
        $response = $this->get(route('materials.index', ['search' => 'aerodinamis']));

        $response->assertStatus(200);
        $response->assertSee('Prinsip Kerja Turbin Angin Modern');
        $response->assertDontSee('Pembangkit Listrik Tenaga Mikrohidro (PLTMH)');

        // 'vampire loads' ada di dalam isi/body materi audit energi
        $responseAudit = $this->get(route('materials.index', ['search' => 'vampire loads']));
        $responseAudit->assertStatus(200);
        $responseAudit->assertSee('Audit Energi dan Konservasi Rumah Tangga');
        $responseAudit->assertDontSee('Prinsip Kerja Turbin Angin Modern');
    }

    /**
     * PBI-016: Parameter query alternatif ?q= juga bekerja sama baiknya.
     */
    public function test_pbi_016_search_supports_q_query_param(): void
    {
        $response = $this->get(route('materials.index', ['q' => 'mikrohidro']));

        $response->assertStatus(200);
        $response->assertSee('Pembangkit Listrik Tenaga Mikrohidro (PLTMH)');
        $response->assertDontSee('Pengenalan Panel Surya Fotovoltaik (PV)');
    }

    /**
     * PBI-016: Tampilan empty state dengan teks eksplisit "Materi tidak ditemukan" jika hasil kosong.
     */
    public function test_pbi_016_empty_state_shows_materi_tidak_ditemukan(): void
    {
        $response = $this->get(route('materials.index', ['search' => 'kata_kunci_yang_pasti_tidak_ada_xyz123']));

        $response->assertStatus(200);
        $response->assertSee('Materi tidak ditemukan');
        $response->assertSee('id="empty-state"', false);
    }

    // =========================================================================
    // PBI-017: Memfilter Materi Berdasarkan Kategori Energi
    // =========================================================================

    /**
     * PBI-017: Filter kategori energi menampilkan hanya materi dari kategori yang terpilih.
     */
    public function test_pbi_017_filter_by_exact_and_slug_category(): void
    {
        // 1. Filter kategori Energi Surya
        $resSurya = $this->get(route('materials.index', ['category' => 'Energi Surya']));
        $resSurya->assertStatus(200);
        $resSurya->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
        $resSurya->assertSee('PLTS Atap &amp; Integrasi Smart Grid', false);
        $resSurya->assertDontSee('Prinsip Kerja Turbin Angin Modern');
        $resSurya->assertDontSee('Pembangkit Listrik Tenaga Mikrohidro (PLTMH)');

        // 2. Filter kategori Energi Angin
        $resAngin = $this->get(route('materials.index', ['category' => 'Energi Angin']));
        $resAngin->assertStatus(200);
        $resAngin->assertSee('Prinsip Kerja Turbin Angin Modern');
        $resAngin->assertSee('Potensi PLTB di Indonesia: Studi Sidrap &amp; Jeneponto', false);
        $resAngin->assertDontSee('Pengenalan Panel Surya Fotovoltaik (PV)');

        // 3. Filter kategori menggunakan format slug atau parsial (surya)
        $resSlug = $this->get(route('materials.index', ['category' => 'surya']));
        $resSlug->assertStatus(200);
        $resSlug->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
        $resSlug->assertDontSee('Prinsip Kerja Turbin Angin Modern');
    }

    /**
     * PBI-017: Filter dengan kategori 'semua' menampilkan seluruh materi.
     */
    public function test_pbi_017_filter_all_categories_returns_all_materials(): void
    {
        $response = $this->get(route('materials.index', ['category' => 'semua']));

        $response->assertStatus(200);
        $response->assertSee('Pengenalan Panel Surya Fotovoltaik (PV)');
        $response->assertSee('Prinsip Kerja Turbin Angin Modern');
        $response->assertSee('Pembangkit Listrik Tenaga Mikrohidro (PLTMH)');
        $response->assertSee('Audit Energi dan Konservasi Rumah Tangga');
    }

    /**
     * PBI-017: Filter kategori yang belum memiliki materi menghasilkan "Materi tidak ditemukan".
     */
    public function test_pbi_017_filter_empty_category_shows_empty_state(): void
    {
        $response = $this->get(route('materials.index', ['category' => 'Geotermal']));

        $response->assertStatus(200);
        $response->assertSee('Materi tidak ditemukan');
    }

    /**
     * PBI-017: Kombinasi filter kategori dan pencarian kata kunci.
     */
    public function test_pbi_017_combined_category_and_search(): void
    {
        // Cari 'PLTB' di dalam kategori 'Energi Angin' -> Cocok
        $match = $this->get(route('materials.index', [
            'category' => 'Energi Angin',
            'search' => 'PLTB'
        ]));
        $match->assertStatus(200);
        $match->assertSee('Potensi PLTB di Indonesia: Studi Sidrap &amp; Jeneponto', false);
        $match->assertDontSee('Pengenalan Panel Surya Fotovoltaik (PV)');

        // Cari 'PLTB' di dalam kategori 'Energi Surya' -> Tidak ada hasil
        $noMatch = $this->get(route('materials.index', [
            'category' => 'Energi Surya',
            'search' => 'PLTB'
        ]));
        $noMatch->assertStatus(200);
        $noMatch->assertSee('Materi tidak ditemukan');
    }

    // =========================================================================
    // PBI-018: Mengurutkan Hasil Materi
    // =========================================================================

    /**
     * PBI-018: Pengurutan Judul A - Z dan Judul Z - A.
     */
    public function test_pbi_018_sort_by_title_az_and_za(): void
    {
        // Sort Judul A-Z
        $resAZ = $this->get(route('materials.index', ['sort' => 'judul-az']));
        $resAZ->assertStatus(200);

        // Ambil urutan title yang dikembalikan pada view
        $materialsAZ = $resAZ->viewData('materials');
        $titlesAZ = $materialsAZ->pluck('title')->toArray();
        $sortedTitlesAZ = $titlesAZ;
        sort($sortedTitlesAZ, SORT_STRING);
        $this->assertSame($sortedTitlesAZ, $titlesAZ);

        // Sort Judul Z-A
        $resZA = $this->get(route('materials.index', ['sort' => 'judul-za']));
        $resZA->assertStatus(200);
        $materialsZA = $resZA->viewData('materials');
        $titlesZA = $materialsZA->pluck('title')->toArray();
        $sortedTitlesZA = $titlesZA;
        rsort($sortedTitlesZA, SORT_STRING);
        $this->assertSame($sortedTitlesZA, $titlesZA);
    }

    /**
     * PBI-018: Pengurutan Terbaru dan Terlama.
     */
    public function test_pbi_018_sort_by_newest_and_oldest(): void
    {
        // Terbaru (default / 'terbaru')
        $resLatest = $this->get(route('materials.index', ['sort' => 'terbaru']));
        $resLatest->assertStatus(200);
        $materialsLatest = $resLatest->viewData('materials');
        $idsLatest = $materialsLatest->pluck('id')->toArray();

        // Terlama ('terlama')
        $resOldest = $this->get(route('materials.index', ['sort' => 'terlama']));
        $resOldest->assertStatus(200);
        $materialsOldest = $resOldest->viewData('materials');
        $idsOldest = $materialsOldest->pluck('id')->toArray();

        $this->assertSame(array_reverse($idsLatest), $idsOldest);
    }

    // =========================================================================
    // Integrasi: Ketiga Parameter (search + category + sort) Bekerja Bersamaan
    // =========================================================================

    /**
     * Ketentuan Eksekusi: Parameter search, category, dan sort bekerja bersamaan dalam satu request.
     */
    public function test_combined_search_category_and_sort_in_single_request(): void
    {
        $response = $this->get(route('materials.index', [
            'search'   => 'energi',
            'category' => 'Energi Air',
            'sort'     => 'judul-az',
        ]));

        $response->assertStatus(200);
        $materials = $response->viewData('materials');
        $this->assertNotEmpty($materials);

        foreach ($materials as $m) {
            $this->assertStringContainsString('Energi Air', $m->category);
        }

        $titles = $materials->pluck('title')->toArray();
        $sortedTitles = $titles;
        sort($sortedTitles, SORT_STRING);
        $this->assertSame($sortedTitles, $titles);
    }

    // =========================================================================
    // API Endpoint JSON Responses (Frontend Fetch Integration)
    // =========================================================================

    /**
     * API endpoint /materials dengan Accept: application/json mengembalikan JSON yang valid.
     */
    public function test_api_endpoint_json_response_with_search_filter_sort(): void
    {
        $response = $this->getJson(route('materials.index', [
            'search'   => 'turbin',
            'category' => 'Energi Angin',
            'sort'     => 'terbaru',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total'   => 1,
            'filters' => [
                'search'   => 'turbin',
                'category' => 'Energi Angin',
                'sort'     => 'terbaru',
            ]
        ]);
        $response->assertJsonFragment([
            'title'    => 'Prinsip Kerja Turbin Angin Modern',
            'category' => 'Energi Angin',
        ]);
    }

    /**
     * API endpoint /api/materials mengembalikan JSON data materi.
     */
    public function test_dedicated_api_route_returns_json(): void
    {
        $response = $this->getJson('/api/materials?search=solar');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'total',
            'filters',
            'categories',
        ]);
    }
}
