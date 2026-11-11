<?php

namespace Tests\Feature;

use Database\Seeders\EnergiKitaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Beranda Website (Fitur F-01: Faris Dzakiy)
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(EnergiKitaSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        
        // PBI-001 Assertions
        $response->assertSee('EnergiKita');
        $response->assertSee('Belajar Energi Bersih untuk Masa Depan');
        $response->assertSee('SDG 7');

        // PBI-002 Assertions (Kategori Energi)
        $response->assertSee('Energi Surya');
        $response->assertSee('Energi Angin');
        $response->assertSee('Energi Air');
        $response->assertSee('Hemat Energi');

        // PBI-003 Assertions (Navigasi & 3 Kartu Akses Cepat)
        $response->assertSee('Materi Energi');
        $response->assertSee('Kuis Cepat');
        $response->assertSee('Misi Harian');

        // Section Ringkasan Progres Belajar
        $response->assertSee('Ringkasan Progres Belajar');
        $response->assertSee('Persentase Penyelesaian Materi');
        $response->assertSee('XP:');
        $response->assertSee('Badge:');
    }
}
