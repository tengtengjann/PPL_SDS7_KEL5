<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Database\Seeders\EnergiKitaSeeder;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialAdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $sreAdmin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EnergiKitaSeeder::class);
        $this->seed(QuestionSeeder::class);

        $this->sreAdmin = User::where('role', 'sre')->firstOrFail();
        $this->student = User::where('role', 'mahasiswa')->firstOrFail();
    }

    /**
     * Otorisasi: Pengunjung (Guest) tidak dapat mengakses endpoint admin.
     */
    public function test_guest_cannot_access_admin_materials(): void
    {
        $response = $this->get(route('admin.materials.index'));
        $response->assertRedirect(route('login'));

        $postResponse = $this->post(route('admin.materials.store'), [
            'title'       => 'Test',
            'category'    => 'Energi Surya',
            'description' => 'Test',
            'body'        => 'Test',
        ]);
        $postResponse->assertRedirect(route('login'));
    }

    /**
     * Otorisasi: Mahasiswa ditolak (403 Forbidden) saat mencoba mengakses panel SRE.
     */
    public function test_student_cannot_access_admin_materials(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.materials.index'));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($this->student)->post(route('admin.materials.store'), [
            'title'       => 'Test Judul',
            'category'    => 'Energi Surya',
            'description' => 'Test Deskripsi',
            'body'        => 'Test Body',
        ]);
        $postResponse->assertStatus(403);
    }

    /**
     * PBI-013: SRE dapat melihat dashboard daftar materi dan halaman tambah materi.
     */
    public function test_sre_can_view_materials_dashboard_and_create_page(): void
    {
        $indexResponse = $this->actingAs($this->sreAdmin)->get(route('admin.materials.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Kelola Materi Pembelajaran');

        $createResponse = $this->actingAs($this->sreAdmin)->get(route('admin.materials.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Tambah Materi Pembelajaran Baru');
    }

    /**
     * PBI-013: SRE dapat menambahkan materi pembelajaran baru tanpa gambar.
     */
    public function test_sre_can_create_material_without_image(): void
    {
        $payload = [
            'title'       => 'Pembangkit Listrik Tenaga Panas Bumi Dieng',
            'category'    => 'Geotermal',
            'description' => 'Pemanfaatan uap panas bumi dari perut bumi Dieng untuk menggerakkan turbin generator.',
            'body'        => 'PLTP Dieng memanfaatkan reservoar geotermal suhu tinggi untuk menghasilkan energi listrik beban dasar yang andal dan ramah lingkungan.',
        ];

        $response = $this->actingAs($this->sreAdmin)->post(route('admin.materials.store'), $payload);

        $response->assertRedirect(route('admin.materials.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('materials', [
            'title'    => 'Pembangkit Listrik Tenaga Panas Bumi Dieng',
            'category' => 'Geotermal',
        ]);

        // Verifikasi materi baru langsung muncul di daftar materi Mahasiswa
        $studentResponse = $this->get(route('materials.index'));
        $studentResponse->assertStatus(200);
        $studentResponse->assertSee('Pembangkit Listrik Tenaga Panas Bumi Dieng');
    }

    /**
     * PBI-013: SRE dapat menambahkan materi baru dengan upload gambar.
     */
    public function test_sre_can_create_material_with_image_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('geotermal-dieng.jpg', 800, 600)->size(500); // 500 KB

        $payload = [
            'title'       => 'Pemanfaatan Biomassa Kelapa Sawit untuk Listrik',
            'category'    => 'Biomassa',
            'description' => 'Mengolah limbah tandan kosong kelapa sawit menjadi bahan bakar boiler pembangkit EBT.',
            'body'        => 'Biomassa sawit memiliki nilai kalor yang cukup tinggi untuk menggantikan batubara pada program co-firing pembangkit listrik di Indonesia.',
            'image'       => $file,
        ];

        $response = $this->actingAs($this->sreAdmin)->post(route('admin.materials.store'), $payload);

        $response->assertRedirect(route('admin.materials.index'));

        $material = Material::where('title', 'Pemanfaatan Biomassa Kelapa Sawit untuk Listrik')->first();
        $this->assertNotNull($material);
        $this->assertNotNull($material->image);

        // Verifikasi file gambar tersimpan di disk public
        Storage::disk('public')->assertExists($material->image);

        // Verifikasi materi muncul di daftar mahasiswa
        $studentResponse = $this->get(route('materials.index'));
        $studentResponse->assertSee('Pemanfaatan Biomassa Kelapa Sawit untuk Listrik');
    }

    /**
     * PBI-013 Edge Cases: Validasi gagal jika field kosong atau file bukan gambar / melebihi 2MB.
     */
    public function test_create_material_validation_edge_cases(): void
    {
        // 1. Validasi form kosong
        $response = $this->actingAs($this->sreAdmin)->post(route('admin.materials.store'), []);
        $response->assertSessionHasErrors(['title', 'category', 'description', 'body']);

        // 2. Validasi file bukan gambar (misal file .txt atau .pdf)
        $invalidFile = UploadedFile::fake()->create('dokumen.pdf', 100);
        $fileResponse = $this->actingAs($this->sreAdmin)->post(route('admin.materials.store'), [
            'title'       => 'Judul Contoh',
            'category'    => 'Energi Surya',
            'description' => 'Deskripsi Contoh',
            'body'        => 'Konten Contoh',
            'image'       => $invalidFile,
        ]);
        $fileResponse->assertSessionHasErrors('image');

        // 3. Validasi ukuran gambar melebihi 2048 KB (misal 3000 KB)
        $largeImage = UploadedFile::fake()->image('huge.jpg')->size(3500);
        $largeResponse = $this->actingAs($this->sreAdmin)->post(route('admin.materials.store'), [
            'title'       => 'Judul Contoh',
            'category'    => 'Energi Surya',
            'description' => 'Deskripsi Contoh',
            'body'        => 'Konten Contoh',
            'image'       => $largeImage,
        ]);
        $largeResponse->assertSessionHasErrors('image');
    }

    /**
     * PBI-014: SRE dapat memperbarui (edit) materi pembelajaran dan gambarnya.
     */
    public function test_sre_can_update_material(): void
    {
        Storage::fake('public');

        $material = Material::first();
        $this->assertNotNull($material);

        $newImage = UploadedFile::fake()->image('updated-cover.jpg')->size(400);

        $payload = [
            'title'       => 'Judul Materi Diperbarui SRE',
            'category'    => 'Hemat Energi',
            'description' => 'Deskripsi telah diperbarui dengan data termutakhir.',
            'body'        => 'Konten teks isi pembelajaran yang telah direvisi secara komprehensif.',
            'image'       => $newImage,
        ];

        $response = $this->actingAs($this->sreAdmin)->put(route('admin.materials.update', $material), $payload);

        $response->assertRedirect(route('admin.materials.index'));
        $response->assertSessionHas('success');

        $material->refresh();
        $this->assertEquals('Judul Materi Diperbarui SRE', $material->title);
        $this->assertEquals('Hemat Energi', $material->category);
        $this->assertEquals('Deskripsi telah diperbarui dengan data termutakhir.', $material->description);
        Storage::disk('public')->assertExists($material->image);

        // Verifikasi perubahan tampil pada tampilan detail Mahasiswa
        $detailResponse = $this->get(route('materials.show', $material->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Judul Materi Diperbarui SRE');
    }

    /**
     * PBI-014: SRE dapat menghapus materi dan materi tersebut tidak lagi tampil di daftar aktif.
     */
    public function test_sre_can_delete_material(): void
    {
        $material = Material::create([
            'title'       => 'Materi Sementara untuk Dihapus',
            'category'    => 'Energi Air',
            'description' => 'Materi ini akan segera dihapus.',
            'body'        => 'Isi materi sementara.',
        ]);

        $this->assertDatabaseHas('materials', ['id' => $material->id]);

        $response = $this->actingAs($this->sreAdmin)->delete(route('admin.materials.destroy', $material));

        $response->assertRedirect(route('admin.materials.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('materials', ['id' => $material->id]);

        // Bersihkan session flash dari aksi destroy sebelumnya
        session()->forget('success');

        // Verifikasi tidak lagi tampil di daftar mahasiswa
        $studentResponse = $this->get(route('materials.index'));
        $studentResponse->assertDontSee('Materi Sementara untuk Dihapus');
        $studentResponse->assertViewHas('materials', function ($materials) use ($material) {
            return !$materials->contains('id', $material->id);
        });
    }

    /**
     * API JSON Support: SRE dapat menambahkan, mengubah, dan menghapus materi via JSON request.
     */
    public function test_admin_materials_supports_json_api(): void
    {
        // 1. JSON Store
        $storeResponse = $this->actingAs($this->sreAdmin)->postJson(route('admin.materials.store'), [
            'title'       => 'Inovasi Smart Microgrid SRE',
            'category'    => 'Energi Surya',
            'description' => 'Integrasi panel surya dan baterai penyimpan.',
            'body'        => 'Smart microgrid memungkinkan kemandirian energi pulau terluar.',
        ]);

        $storeResponse->assertStatus(201);
        $storeResponse->assertJsonPath('success', true);
        $createdId = $storeResponse->json('data.id');

        // 2. JSON Update
        $updateResponse = $this->actingAs($this->sreAdmin)->putJson(route('admin.materials.update', $createdId), [
            'title'       => 'Inovasi Smart Microgrid SRE v2',
            'category'    => 'Energi Surya',
            'description' => 'Deskripsi update JSON.',
            'body'        => 'Body update JSON.',
        ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJsonPath('data.title', 'Inovasi Smart Microgrid SRE v2');

        // 3. JSON Destroy
        $deleteResponse = $this->actingAs($this->sreAdmin)->deleteJson(route('admin.materials.destroy', $createdId));
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJsonPath('success', true);
    }
}
