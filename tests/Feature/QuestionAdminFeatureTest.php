<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Database\Seeders\EnergiKitaSeeder;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionAdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $sreAdmin;
    protected User $student;
    protected Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EnergiKitaSeeder::class);
        $this->seed(QuestionSeeder::class);

        $this->sreAdmin = User::where('role', 'sre')->firstOrFail();
        $this->student = User::where('role', 'mahasiswa')->firstOrFail();
        $this->quiz = Quiz::firstOrFail();
    }

    /**
     * Otorisasi: Mahasiswa tidak boleh mengakses endpoint manajemen soal kuis (403 Forbidden).
     */
    public function test_student_cannot_manage_questions(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.quizzes.questions.index', $this->quiz));
        $response->assertStatus(403);

        $createResponse = $this->actingAs($this->student)->post(route('admin.quizzes.questions.store', $this->quiz), [
            'question' => 'Contoh pertanyaan?',
            'options'  => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
            'answer'   => 'A',
        ]);
        $createResponse->assertStatus(403);
    }

    /**
     * PBI-015: SRE dapat melihat daftar soal dan halaman tambah soal untuk kuis tertentu.
     */
    public function test_sre_can_view_questions_list_and_create_page(): void
    {
        $indexResponse = $this->actingAs($this->sreAdmin)->get(route('admin.quizzes.questions.index', $this->quiz));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($this->quiz->title);
        $indexResponse->assertSee('Tambah Soal Baru');

        $createResponse = $this->actingAs($this->sreAdmin)->get(route('admin.quizzes.questions.create', $this->quiz));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Tambah Soal Pilihan Ganda Baru');
    }

    /**
     * PBI-015: SRE dapat menambahkan butir soal kuis baru dengan 4 opsi, kunci jawaban, dan pembahasan.
     */
    public function test_sre_can_create_question_with_options_and_answer_key(): void
    {
        $payload = [
            'question'    => 'Apa kepanjangan dari PLTB dalam pembangkitan energi terbarukan?',
            'options'     => [
                'A' => 'Pembangkit Listrik Tenaga Bayu',
                'B' => 'Pembangkit Listrik Tenaga Batubara',
                'C' => 'Pembangkit Listrik Tenaga Biogas',
                'D' => 'Pembangkit Listrik Tenaga Baterai',
            ],
            'answer'      => 'A',
            'explanation' => 'PLTB adalah singkatan dari Pembangkit Listrik Tenaga Bayu (angin), memanfaatkan hembusan angin untuk memutar rotor turbin.',
        ];

        $response = $this->actingAs($this->sreAdmin)->post(route('admin.quizzes.questions.store', $this->quiz), $payload);

        $response->assertRedirect(route('admin.quizzes.questions.index', $this->quiz));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'quiz_id'  => $this->quiz->id,
            'question' => 'Apa kepanjangan dari PLTB dalam pembangkitan energi terbarukan?',
            'answer'   => 'A',
        ]);

        // Verifikasi soal tampil di halaman pengerjaan kuis Mahasiswa
        $studentQuizResponse = $this->get(route('quiz.show', $this->quiz));
        $studentQuizResponse->assertStatus(200);
        $studentQuizResponse->assertSee('Apa kepanjangan dari PLTB dalam pembangkitan energi terbarukan?');
        $studentQuizResponse->assertSee('Pembangkit Listrik Tenaga Bayu');
    }

    /**
     * PBI-015 Edge Cases: Validasi gagal jika kunci jawaban tidak valid atau ada opsi yang kosong.
     */
    public function test_create_question_validation_edge_cases(): void
    {
        // 1. Kunci jawaban tidak valid (misal 'E' atau tidak dipilih)
        $invalidAnswerResponse = $this->actingAs($this->sreAdmin)->post(route('admin.quizzes.questions.store', $this->quiz), [
            'question' => 'Contoh soal?',
            'options'  => [
                'A' => 'Pilihan 1',
                'B' => 'Pilihan 2',
                'C' => 'Pilihan 3',
                'D' => 'Pilihan 4',
            ],
            'answer'   => 'Z', // Tidak valid
        ]);
        $invalidAnswerResponse->assertSessionHasErrors('answer');

        // 2. Salah satu opsi kosong
        $missingOptionResponse = $this->actingAs($this->sreAdmin)->post(route('admin.quizzes.questions.store', $this->quiz), [
            'question' => 'Contoh soal?',
            'options'  => [
                'A' => 'Pilihan A',
                'B' => '', // Kosong
                'C' => 'Pilihan C',
                'D' => 'Pilihan D',
            ],
            'answer'   => 'A',
        ]);
        $missingOptionResponse->assertSessionHasErrors('options.B');

        // 3. Pertanyaan kosong
        $emptyQuestionResponse = $this->actingAs($this->sreAdmin)->post(route('admin.quizzes.questions.store', $this->quiz), [
            'question' => '',
            'options'  => [
                'A' => 'Pilihan A',
                'B' => 'Pilihan B',
                'C' => 'Pilihan C',
                'D' => 'Pilihan D',
            ],
            'answer'   => 'A',
        ]);
        $emptyQuestionResponse->assertSessionHasErrors('question');
    }

    /**
     * PBI-015: SRE dapat mengubah butir soal, opsi jawaban, kunci jawaban, dan pembahasan.
     */
    public function test_sre_can_update_question(): void
    {
        $question = $this->quiz->questions()->first();
        $this->assertNotNull($question);

        $payload = [
            'question'    => 'Pertanyaan kuis yang telah direvisi oleh tim SRE?',
            'options'     => [
                'A' => 'Jawaban Alternatif 1',
                'B' => 'Jawaban Alternatif 2 (Revisi Benar)',
                'C' => 'Jawaban Alternatif 3',
                'D' => 'Jawaban Alternatif 4',
            ],
            'answer'      => 'B',
            'explanation' => 'Pembahasan terbaru telah disesuaikan dengan kurikulum terkini.',
        ];

        $response = $this->actingAs($this->sreAdmin)->put(
            route('admin.quizzes.questions.update', [$this->quiz, $question]),
            $payload
        );

        $response->assertRedirect(route('admin.quizzes.questions.index', $this->quiz));
        $response->assertSessionHas('success');

        $question->refresh();
        $this->assertEquals('Pertanyaan kuis yang telah direvisi oleh tim SRE?', $question->question);
        $this->assertEquals('B', $question->answer);
        $this->assertEquals('Jawaban Alternatif 2 (Revisi Benar)', $question->options['B']);
        $this->assertEquals('Pembahasan terbaru telah disesuaikan dengan kurikulum terkini.', $question->explanation);
    }

    /**
     * PBI-015: SRE dapat menghapus soal kuis.
     */
    public function test_sre_can_delete_question(): void
    {
        $question = Question::create([
            'quiz_id'     => $this->quiz->id,
            'question'    => 'Soal uji coba yang akan dihapus SRE?',
            'options'     => [
                'A' => 'Opsi A',
                'B' => 'Opsi B',
                'C' => 'Opsi C',
                'D' => 'Opsi D',
            ],
            'answer'      => 'A',
            'explanation' => 'Penjelasan sementara.',
        ]);

        $this->assertDatabaseHas('questions', ['id' => $question->id]);

        $response = $this->actingAs($this->sreAdmin)->delete(
            route('admin.quizzes.questions.destroy', [$this->quiz, $question])
        );

        $response->assertRedirect(route('admin.quizzes.questions.index', $this->quiz));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /**
     * PBI-015: Shortcut pengelolaan soal dari Materi (Material -> Quiz).
     */
    public function test_sre_can_access_questions_via_material_shortcut(): void
    {
        $material = Material::first();
        $this->assertNotNull($material);

        $response = $this->actingAs($this->sreAdmin)->get(route('admin.materials.questions', $material));
        $response->assertRedirect();
        
        // Mengikuti redirect ke halaman kelola soal
        $followResponse = $this->actingAs($this->sreAdmin)->get($response->headers->get('Location'));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Kelola Soal Kuis');
    }
}
