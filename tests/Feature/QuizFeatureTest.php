<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Database\Seeders\EnergiKitaSeeder;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EnergiKitaSeeder::class);
        $this->seed(QuestionSeeder::class);
    }

    public function test_can_view_quiz_list(): void
    {
        $response = $this->get(route('quiz.index'));

        $response->assertStatus(200);
        $response->assertSee('Kuis Cepat Energi Bersih');
    }

    public function test_can_view_quiz_questions_and_options(): void
    {
        $quiz = Quiz::has('questions')->first();
        $this->assertNotNull($quiz, 'Quiz with questions should exist');

        $response = $this->get(route('quiz.show', $quiz));

        $response->assertStatus(200);
        $response->assertSee($quiz->title);

        foreach ($quiz->questions as $question) {
            $response->assertSee($question->question);
            foreach ($question->options as $opt) {
                $response->assertSee($opt);
            }
        }
    }

    public function test_submit_quiz_requires_all_answers(): void
    {
        $quiz = Quiz::has('questions')->first();

        // Submit without any answers
        $response = $this->post(route('quiz.submit', $quiz), []);

        $response->assertSessionHasErrors();
    }

    public function test_submit_quiz_calculates_score_and_shows_review(): void
    {
        $quiz = Quiz::has('questions')->first();
        $questions = $quiz->questions;

        // Build answers: make first answer correct, others deliberately incorrect
        $answers = [];
        foreach ($questions as $index => $q) {
            if ($index === 0) {
                $answers[$q->id] = $q->answer; // Correct
            } else {
                $answers[$q->id] = ($q->answer === 'A') ? 'B' : 'A'; // Wrong
            }
        }

        $response = $this->post(route('quiz.submit', $quiz), [
            'answers' => $answers,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Hasil Kuis');
        $response->assertSee('Pembahasan');
        $response->assertSee('Benar');
        $response->assertSee('Salah');

        // Check that explanation is displayed
        if (!empty($questions[0]->explanation)) {
            $response->assertSee($questions[0]->explanation);
        }
    }

    public function test_authenticated_student_earns_xp_and_records_result(): void
    {
        $student = User::where('role', 'mahasiswa')->first();
        $this->assertNotNull($student);

        $initialXp = $student->xp;

        // Pilih kuis yang belum pernah dikerjakan atau reset nilainya
        $quiz = Quiz::has('questions')->first();
        QuizResult::where('user_id', $student->id)->where('quiz_id', $quiz->id)->delete();

        // Jawab semua pertanyaan dengan benar
        $answers = [];
        foreach ($quiz->questions as $q) {
            $answers[$q->id] = $q->answer;
        }

        $response = $this->actingAs($student)->post(route('quiz.submit', $quiz), [
            'answers' => $answers,
        ]);

        $response->assertStatus(200);
        $response->assertSee('+50 XP');

        // Pastikan QuizResult tercatat di database
        $this->assertDatabaseHas('quiz_results', [
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
            'score'   => 100,
        ]);

        // Pastikan XP bertambah 50
        $student->refresh();
        $this->assertEquals($initialXp + 50, $student->xp);
    }
}
