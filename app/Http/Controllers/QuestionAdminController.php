<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionAdminController extends Controller
{
    /**
     * Tampilkan daftar soal untuk kuis tertentu.
     */
    public function index(Quiz $quiz, Request $request): View|JsonResponse
    {
        $quiz->load('material', 'questions');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success'   => true,
                'quiz'      => $quiz,
                'questions' => $quiz->questions,
            ]);
        }

        return view('pages.admin.question-index', compact('quiz'));
    }

    /**
     * Helper untuk membuka daftar soal kuis langsung dari materi.
     */
    public function indexByMaterial(Material $material)
    {
        $quiz = Quiz::firstOrCreate(
            ['material_id' => $material->id],
            ['title' => 'Kuis: ' . $material->title]
        );

        return redirect()->route('admin.quizzes.questions.index', $quiz);
    }

    /**
     * Tampilkan form tambah soal kuis baru.
     */
    public function create(Quiz $quiz): View
    {
        $quiz->load('material');

        return view('pages.admin.question-form', [
            'quiz'      => $quiz,
            'question'  => null,
            'isEditing' => false,
        ]);
    }

    /**
     * Simpan soal kuis baru beserta opsi dan kunci jawaban (PBI-015).
     */
    public function store(Request $request, Quiz $quiz): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'question'    => 'required|string',
            'options'     => 'required|array|min:2',
            'options.A'   => 'required|string',
            'options.B'   => 'required|string',
            'options.C'   => 'required|string',
            'options.D'   => 'required|string',
            'answer'      => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
        ], [
            'question.required'  => 'Pertanyaan soal kuis wajib diisi.',
            'options.required'   => 'Pilihan opsi jawaban wajib disediakan.',
            'options.A.required' => 'Teks untuk Pilihan A wajib diisi.',
            'options.B.required' => 'Teks untuk Pilihan B wajib diisi.',
            'options.C.required' => 'Teks untuk Pilihan C wajib diisi.',
            'options.D.required' => 'Teks untuk Pilihan D wajib diisi.',
            'answer.required'    => 'Kunci jawaban yang benar wajib dipilih.',
            'answer.in'          => 'Kunci jawaban harus merupakan salah satu pilihan valid (A, B, C, atau D).',
        ]);

        $createdQuestion = Question::create([
            'quiz_id'     => $quiz->id,
            'question'    => $validated['question'],
            'options'     => $validated['options'],
            'answer'      => strtoupper($validated['answer']),
            'explanation' => $validated['explanation'] ?? null,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Soal kuis berhasil ditambahkan.',
                'data'    => $createdQuestion,
            ], 201);
        }

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Soal baru berhasil ditambahkan ke kuis!');
    }

    /**
     * Tampilkan form edit soal kuis (PBI-015).
     */
    public function edit(Quiz $quiz, Question $question): View
    {
        $quiz->load('material');

        return view('pages.admin.question-form', [
            'quiz'      => $quiz,
            'question'  => $question,
            'isEditing' => true,
        ]);
    }

    /**
     * Perbarui soal kuis, opsi jawaban, kunci, atau pembahasan (PBI-015).
     */
    public function update(Request $request, Quiz $quiz, Question $question): RedirectResponse|JsonResponse
    {
        // Pastikan soal milik kuis yang bersangkutan
        if ($question->quiz_id !== $quiz->id) {
            abort(404, 'Soal tidak ditemukan pada kuis ini.');
        }

        $validated = $request->validate([
            'question'    => 'required|string',
            'options'     => 'required|array|min:2',
            'options.A'   => 'required|string',
            'options.B'   => 'required|string',
            'options.C'   => 'required|string',
            'options.D'   => 'required|string',
            'answer'      => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
        ], [
            'question.required'  => 'Pertanyaan soal kuis wajib diisi.',
            'options.required'   => 'Pilihan opsi jawaban wajib disediakan.',
            'options.A.required' => 'Teks untuk Pilihan A wajib diisi.',
            'options.B.required' => 'Teks untuk Pilihan B wajib diisi.',
            'options.C.required' => 'Teks untuk Pilihan C wajib diisi.',
            'options.D.required' => 'Teks untuk Pilihan D wajib diisi.',
            'answer.required'    => 'Kunci jawaban yang benar wajib dipilih.',
            'answer.in'          => 'Kunci jawaban harus merupakan salah satu pilihan valid (A, B, C, atau D).',
        ]);

        $question->update([
            'question'    => $validated['question'],
            'options'     => $validated['options'],
            'answer'      => strtoupper($validated['answer']),
            'explanation' => $validated['explanation'] ?? null,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Soal kuis berhasil diperbarui.',
                'data'    => $question,
            ]);
        }

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Soal kuis berhasil diperbarui!');
    }

    /**
     * Hapus soal kuis (PBI-015).
     */
    public function destroy(Request $request, Quiz $quiz, Question $question): RedirectResponse|JsonResponse
    {
        // Pastikan soal milik kuis yang bersangkutan
        if ($question->quiz_id !== $quiz->id) {
            abort(404, 'Soal tidak ditemukan pada kuis ini.');
        }

        $question->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Soal kuis berhasil dihapus.',
            ]);
        }

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Soal kuis berhasil dihapus.');
    }
}
