<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Menampilkan daftar semua kuis yang tersedia.
     */
    public function index()
    {
        $quizzes = Quiz::with('material', 'questions')->get();

        return view('quiz.index', compact('quizzes'));
    }

    /**
     * Menampilkan halaman pengerjaan kuis (soal pilihan ganda).
     */
    public function show(Quiz $quiz)
    {
        $quiz->load('questions', 'material');

        // Pastikan kuis memiliki soal
        if ($quiz->questions->isEmpty()) {
            return redirect()
                ->route('quiz.index')
                ->with('info', 'Kuis ini belum memiliki soal.');
        }

        return view('quiz.show', compact('quiz'));
    }

    /**
     * Memvalidasi jawaban, menghitung skor, dan menampilkan hasil beserta pembahasan.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions', 'material');

        // Bangun aturan validasi dinamis: setiap soal wajib dijawab
        $rules = [];
        $messages = [];
        foreach ($quiz->questions as $index => $question) {
            $rules["answers.{$question->id}"] = 'required|in:A,B,C,D';
            $messages["answers.{$question->id}.required"] = 'Soal nomor ' . ($index + 1) . ' wajib dijawab.';
            $messages["answers.{$question->id}.in"] = 'Jawaban soal nomor ' . ($index + 1) . ' tidak valid.';
        }

        $validated = $request->validate($rules, $messages);

        $answers = $validated['answers']; // ['question_id' => 'A/B/C/D']

        // Hitung skor
        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = strtoupper($userAnswer) === strtoupper($question->answer);

            if ($isCorrect) {
                $correctCount++;
            }

            $results[] = [
                'question'     => $question,
                'user_answer'  => $userAnswer,
                'is_correct'   => $isCorrect,
            ];
        }

        $wrongCount = $totalQuestions - $correctCount;
        $score = $totalQuestions > 0
            ? round(($correctCount / $totalQuestions) * 100)
            : 0;

        // Gamifikasi: Jika mahasiswa/user sedang login, catat hasil dan beri XP
        $xpEarned = 0;
        if (Auth::check()) {
            $user = Auth::user();

            $previousResult = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->first();

            QuizResult::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                ],
                [
                    'score' => $score,
                ]
            );

            // Beri +50 XP jika lulus (minimal 60) dan belum pernah lulus sebelumnya
            if ($score >= 60 && (!$previousResult || $previousResult->score < 60)) {
                $xpEarned = 50;
                $user->increment('xp', $xpEarned);
            }

            // Jika nilai memuaskan (>= 80), tandai modul materi terkait sebagai 'completed'
            if ($score >= 80 && $quiz->material_id) {
                Progress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'material_id' => $quiz->material_id,
                    ],
                    [
                        'status' => 'completed',
                    ]
                );
            }
        }

        return view('quiz.result', compact(
            'quiz',
            'results',
            'score',
            'correctCount',
            'wrongCount',
            'totalQuestions',
            'xpEarned'
        ));
    }
}
