<?php

namespace App\Http\Controllers;

use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class QuizHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat dan nilai kuis mahasiswa yang sedang login.
     * Data hanya diambil berdasarkan Auth::id(), bukan parameter request.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua hasil kuis milik user, eager load quiz & material, urutkan terbaru
        $quizResults = QuizResult::where('user_id', $user->id)
            ->with(['quiz.material'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pages.quiz-history', compact('user', 'quizResults'));
    }
}
