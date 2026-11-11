<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\EnergiKitaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan seeder data awal untuk memastikan data uji tersedia
        $this->seed(EnergiKitaSeeder::class);
    }

    /**
     * Acceptance Criteria PBI-001: Pengenalan EnergiKita & SDG 7
     * 1. Beranda terbuka dengan status 200.
     * 2. Beranda menampilkan nama ("EnergiKita") dan tujuan EnergiKita.
     * 3. Ringkasan SDG 7 tentang energi bersih dan terjangkau dapat dibaca.
     */
    public function test_pbi_001_beranda_displays_introduction_and_sdg_7_summary(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Memastikan nama & tujuan platform EnergiKita ditampilkan
        $response->assertSee('EnergiKita');
        $response->assertSee('Belajar Energi Bersih untuk Masa Depan');
        $response->assertSee('Kenali energi surya, angin, air, dan cara hemat energi');

        // Memastikan ringkasan SDG 7 ditampilkan
        $response->assertSee('SDG 7');
        $response->assertSee('SDG 7: Energi Bersih dan Terjangkau');
        $response->assertSee('Target 7.1');
        $response->assertSee('Akses Universal');
        $response->assertSee('Target 7.2');
        $response->assertSee('Energi Terbarukan');
        $response->assertSee('Target 7.3');
        $response->assertSee('Efisiensi / Hemat Energi');
        $response->assertSee('Organisasi SRE (Society of Renewable Energy)');
    }

    /**
     * Acceptance Criteria PBI-002: Ringkasan Kategori Energi
     * 1. Beranda menampilkan daftar kategori energi (Energi Surya, Energi Angin, Energi Air, Hemat Energi).
     * 2. Setiap kategori memiliki nama, ikon visual, ringkasan singkat yang relevan, dan jumlah materi aktif.
     */
    public function test_pbi_002_beranda_displays_energy_categories_and_summaries(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Memastikan seluruh nama kategori energi tampil
        $response->assertSee('Energi Surya');
        $response->assertSee('Energi Angin');
        $response->assertSee('Energi Air');
        $response->assertSee('Hemat Energi');

        // 2. Memastikan ringkasan deskripsi kategori tampil
        $response->assertSee('fotovoltaik (PV)');
        $response->assertSee('PLTB Sidrap &amp; Jeneponto', false);
        $response->assertSee('Mikrohidro (PLTMH)');
        $response->assertSee('audit energi mandiri');

        // 3. Memastikan jumlah materi aktif per kategori tampil dinamis dari database (masing-masing 2 modul)
        $response->assertSee('2 Materi');

        // 4. Memastikan tautan eksplorasi kategori menuju route materi kategori
        $response->assertSee(route('materials.category', 'energi-surya'));
        $response->assertSee(route('materials.category', 'energi-angin'));
        $response->assertSee(route('materials.category', 'energi-air'));
        $response->assertSee(route('materials.category', 'hemat-energi'));
    }

    /**
     * Acceptance Criteria PBI-003: Navigasi Fitur Utama
     * 1. Header/Navbar menyediakan navigasi ke Beranda, Belajar, Kuis, Game, Peringkat, serta Profil/Login.
     * 2. Tersedia 3 Kartu Navigasi Cepat (Materi Energi, Kuis Cepat, Misi Harian).
     * 3. Setiap tautan aktif dan dapat diakses (status 200).
     */
    public function test_pbi_003_beranda_provides_main_navigation_and_quick_access_cards(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Validasi Tautan Navigasi di Header / Navbar
        $response->assertSee(route('home'));
        $response->assertSee(route('materials.index'));
        $response->assertSee(route('quizzes.index'));
        $response->assertSee(route('games.index'));
        $response->assertSee(route('leaderboard.index'));
        $response->assertSee(route('profile.index'));

        // 2. Validasi 3 Kartu Navigasi Cepat di Halaman Beranda
        $response->assertSee('Materi Energi');
        $response->assertSee('Pelajari topik');
        $response->assertSee('Kuis Cepat');
        $response->assertSee('Uji pemahaman');
        $response->assertSee('Misi Harian');
        $response->assertSee('Kumpulkan XP');

        // 3. Memastikan semua named routes tujuan dapat diakses tanpa error (status 200)
        $this->get(route('materials.index'))->assertStatus(200);
        $this->get(route('quizzes.index'))->assertStatus(200);
        $this->get(route('missions.index'))->assertStatus(200);
        $this->get(route('games.index'))->assertStatus(200);
        $this->get(route('leaderboard.index'))->assertStatus(200);
        $this->get(route('profile.index'))->assertStatus(200);
    }

    /**
     * Validasi Section Ringkasan Progres Belajar Pengguna
     * Menguji tampilan saat login (Mahasiswa Faris) dan saat belum login (Guest).
     */
    public function test_progress_section_displays_student_xp_and_badge_accurately(): void
    {
        // 1. Pengujian untuk Pengguna Guest (Belum Login)
        $guestResponse = $this->get(route('home'));
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Ringkasan Progres Belajar');
        $guestResponse->assertSee('Persentase Penyelesaian Materi');
        $guestResponse->assertSee('0%');
        $guestResponse->assertSee('XP: 0');
        $guestResponse->assertSee('Badge: Energy Beginner');

        // 2. Pengujian untuk Mahasiswa Login (Faris Dzakiy, 85 XP, Badge: Green Explorer, 2 materi selesai)
        $student = User::where('email', 'faris@student.ac.id')->first();
        $this->assertNotNull($student);

        $authResponse = $this->actingAs($student)->get(route('home'));
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Semangat Belajar, Faris Dzakiy!');
        $authResponse->assertSee('XP: 85');
        $authResponse->assertSee('Badge: Green Explorer');
        $authResponse->assertSee('25%'); // 2 dari 8 materi = 25%
        $authResponse->assertSee('2 dari 8 materi telah diselesaikan');
    }
}
