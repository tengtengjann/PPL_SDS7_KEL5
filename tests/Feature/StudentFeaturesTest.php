<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data dasar aplikasi
        $this->seed(\Database\Seeders\EnergiKitaSeeder::class);
    }

    // =========================================================================
    // TEST 1: FITUR STUDENT PROFILE
    // =========================================================================

    public function test_profile_requires_authentication(): void
    {
        // Unauthenticated guest harus di-redirect ke halaman login
        $response = $this->get(route('profile.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_student_can_view_own_profile_with_real_data(): void
    {
        $student = User::where('email', 'faris@student.ac.id')->first();
        $this->assertNotNull($student);

        $response = $this->actingAs($student)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee($student->email);
        $response->assertSee('Mahasiswa');
        $response->assertSee((string) $student->xp);
    }

    public function test_different_student_sees_only_own_profile(): void
    {
        $student2 = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@student.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
            'xp'       => 200,
        ]);

        $response = $this->actingAs($student2)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@student.ac.id');
        $response->assertDontSee('faris@student.ac.id');
    }

    // =========================================================================
    // TEST 2: FITUR LEARNING MATERIAL PROGRESS
    // =========================================================================

    public function test_learning_progress_requires_authentication(): void
    {
        $response = $this->get(route('learning-progress.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_learning_progress_displays_all_materials_and_correct_stats(): void
    {
        $student = User::where('email', 'faris@student.ac.id')->first();
        $totalMaterials = Material::count();
        $completedMaterials = Progress::where('user_id', $student->id)->where('status', 'completed')->count();

        $response = $this->actingAs($student)->get(route('learning-progress.index'));

        $response->assertStatus(200);
        $response->assertSee('Progres Belajar Saya');
        $response->assertSee((string) $totalMaterials);
        $response->assertSee('Completed');

        // Pastikan setiap judul materi muncul di daftar
        foreach (Material::all() as $m) {
            $response->assertSee($m->title);
        }
    }

    public function test_learning_progress_does_not_mix_between_students(): void
    {
        $student1 = User::where('email', 'faris@student.ac.id')->first();

        $student2 = User::create([
            'name'     => 'Dewi Lestari',
            'email'    => 'dewi@student.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
            'xp'       => 0,
        ]);

        // Student2 belum menyelesaikan materi apa pun
        $response2 = $this->actingAs($student2)->get(route('learning-progress.index'));
        $response2->assertStatus(200);
        $response2->assertSee('0%'); // Persentase harus 0%

        // Student1 sudah ada progres (2 selesai dari seeder)
        $response1 = $this->actingAs($student1)->get(route('learning-progress.index'));
        $response1->assertStatus(200);
        $expectedPercentage = round((2 / Material::count()) * 100);
        $response1->assertSee($expectedPercentage . '%');
    }

    public function test_student_can_mark_material_as_completed(): void
    {
        $student = User::create([
            'name'     => 'Andi Wijaya',
            'email'    => 'andi@student.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
            'xp'       => 0,
        ]);

        $material = Material::first();

        // Tandai selesai
        $response = $this->actingAs($student)->post(route('learning-progress.complete', $material->id));
        $response->assertRedirect(route('learning-progress.index'));

        // Pastikan status di database berubah jadi completed
        $this->assertDatabaseHas('progress', [
            'user_id'     => $student->id,
            'material_id' => $material->id,
            'status'      => 'completed',
        ]);
    }

    // =========================================================================
    // TEST 3: FITUR QUIZ HISTORY AND SCORES
    // =========================================================================

    public function test_quiz_history_requires_authentication(): void
    {
        $response = $this->get(route('quiz-history.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_quiz_history_displays_scores_and_dates_correctly(): void
    {
        $student = User::where('email', 'faris@student.ac.id')->first();
        $quizResult = QuizResult::where('user_id', $student->id)->with('quiz')->first();
        $this->assertNotNull($quizResult);

        $response = $this->actingAs($student)->get(route('quiz-history.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat');
        $response->assertSee('Nilai Kuis');
        $response->assertSee((string) $quizResult->score);
    }

    public function test_quiz_history_does_not_mix_between_students(): void
    {
        $student1 = User::where('email', 'faris@student.ac.id')->first();

        $student2 = User::create([
            'name'     => 'Siti Nurhaliza',
            'email'    => 'siti@student.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
            'xp'       => 0,
        ]);

        // Student2 belum pernah mengerjakan kuis -> harus melihat empty state
        $response2 = $this->actingAs($student2)->get(route('quiz-history.index'));
        $response2->assertStatus(200);
        $response2->assertSee('Belum Ada Riwayat Kuis');

        // Student1 punya riwayat kuis
        $response1 = $this->actingAs($student1)->get(route('quiz-history.index'));
        $response1->assertStatus(200);
        $response1->assertDontSee('Belum Ada Riwayat Kuis');
        $response1->assertSee('Daftar Riwayat Pengerjaan Kuis');
    }
}
