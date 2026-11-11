<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Database\Seeders\EnergiKitaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BerandaFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EnergiKitaSeeder::class);
    }

    /**
     * PBI-001: Pengenalan EnergiKita & SDG 7
     */
    public function test_pbi_001_beranda_displays_introduction_and_sdg_7_summary(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('EnergiKita');
        $response->assertSee('Belajar Energi Bersih untuk Masa Depan');
        $response->assertSee('SDG 7: Energi Bersih dan Terjangkau');
        $response->assertSee('Target 7.1');
        $response->assertSee('Target 7.2');
        $response->assertSee('Target 7.3');
        $response->assertSee('Akses Universal');
        $response->assertSee('Energi Terbarukan');
        $response->assertSee('Organisasi SRE (Society of Renewable Energy)');
    }

    /**
     * PBI-002: Ringkasan Kategori Energi (Energi Surya, Energi Angin, Energi Air, Hemat Energi)
     */
    public function test_pbi_002_beranda_displays_energy_categories_with_dynamic_material_counts(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        
        // Cek 4 kategori utama
        $response->assertSee('Energi Surya');
        $response->assertSee('Energi Angin');
        $response->assertSee('Energi Air');
        $response->assertSee('Hemat Energi');

        // Cek perhitungan jumlah materi per kategori (masing-masing 2 modul di database)
        $response->assertSee('2 Materi');
    }

    /**
     * PBI-003: Navigasi Fitur Utama (Named Routes & 3 Kartu Akses Cepat)
     */
    public function test_pbi_003_beranda_navigates_to_all_main_features_without_broken_links(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Header / Navbar named routes (sesuai spesifikasi)
        $response->assertSee(route('home'));
        $response->assertSee(route('materials.index'));
        $response->assertSee(route('quizzes.index'));
        $response->assertSee(route('games.index'));
        $response->assertSee(route('leaderboard.index'));
        $response->assertSee(route('profile.index'));

        // 3 Kartu Akses Cepat
        $response->assertSee('Materi Energi');
        $response->assertSee('Pelajari topik');
        $response->assertSee('Kuis Cepat');
        $response->assertSee('Uji pemahaman');
        $response->assertSee('Misi Harian');
        $response->assertSee('Kumpulkan XP');

        // Pastikan semua named routes utama dapat diakses langsung (status 200)
        $this->get(route('materials.index'))->assertStatus(200);
        $this->get(route('quizzes.index'))->assertStatus(200);
        $this->get(route('missions.index'))->assertStatus(200);
        $this->get(route('games.index'))->assertStatus(200);
        $this->get(route('leaderboard.index'))->assertStatus(200);
        $this->get(route('profile.index'))->assertStatus(200);
    }

    /**
     * Section Ringkasan Progres Belajar: Mahasiswa Login (Faris Dzakiy)
     */
    public function test_progress_section_displays_student_progress_xp_and_badge(): void
    {
        $student = User::where('email', 'faris@student.ac.id')->first();
        $this->assertNotNull($student);

        $response = $this->actingAs($student)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Semangat Belajar, Faris Dzakiy!');
        $response->assertSee('XP: 85');
        $response->assertSee('Badge: Green Explorer');
        $response->assertSee('Persentase Penyelesaian Materi');
        // Total 8 materi, 2 selesai = 25%
        $response->assertSee('25%');
        $response->assertSee('2 dari 8 materi telah diselesaikan');
    }

    /**
     * Section Ringkasan Progres Belajar: Guest / Belum Login
     */
    public function test_progress_section_displays_guest_defaults_when_not_logged_in(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Persentase Penyelesaian Materi');
        $response->assertSee('0%');
        $response->assertSee('XP: 0');
        $response->assertSee('Badge: Energy Beginner');
    }
}
