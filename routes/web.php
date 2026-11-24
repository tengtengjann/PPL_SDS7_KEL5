<?php

use App\Http\Controllers\BadgeAdminController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningProgressController;
use App\Http\Controllers\MaterialAdminController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionAdminController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizHistoryController;
use App\Models\Badge;
use App\Models\Material;
use App\Models\Mission;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - EnergiKita (SDG 7)
|--------------------------------------------------------------------------
*/

// =========================================================================
// Halaman Beranda (Fitur F-01: Faris Dzakiy - Sprint 1)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// =========================================================================
// Navigasi Fitur Utama (PBI-003): Named Routes Placeholder
// =========================================================================

// 1. Belajar / Materi Energi (materials.index) - Modul F-06
Route::prefix('materials')->name('materials.')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])->name('index');
    Route::get('/category/{category}', [MaterialController::class, 'category'])->name('category');
    Route::get('/{id}', [MaterialController::class, 'show'])->name('show');
});

// Endpoint API pendukung untuk F-06
Route::get('/api/materials', [MaterialController::class, 'index'])->name('web.api.materials.index');

// Alias rute bahasa Indonesia untuk fleksibilitas
Route::get('/materi', function () {
    return redirect()->route('materials.index');
})->name('materi.index');

Route::get('/materi/kategori/{category}', function ($category) {
    return redirect()->route('materials.category', $category);
})->name('materi.category');

Route::get('/materi/{id}', function ($id) {
    return redirect()->route('materials.show', $id);
})->name('materi.show');


// 2. Kuis Cepat (quizzes.index) — Tetap mempertahankan rute lama
Route::prefix('quizzes')->name('quizzes.')->group(function () {
    Route::get('/', function () {
        $quizzes = Quiz::with('material', 'questions')->get();
        return view('pages.kuis-index', compact('quizzes'));
    })->name('index');
});
Route::get('/kuis', function () {
    return redirect()->route('quizzes.index');
})->name('kuis.index');

// 2b. Fitur Pengerjaan Kuis Pilihan Ganda (QuizController)
Route::prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/',           [QuizController::class, 'index'])->name('index');
    Route::get('/{quiz}',     [QuizController::class, 'show'])->name('show');
    Route::post('/{quiz}/submit', [QuizController::class, 'submit'])->name('submit');
});


// 3. Misi Harian (missions.index)
Route::prefix('missions')->name('missions.')->group(function () {
    Route::get('/', function () {
        $missions = Mission::all();
        return view('pages.misi-index', compact('missions'));
    })->name('index');
});
Route::get('/misi', function () {
    return redirect()->route('missions.index');
})->name('misi.index');


// 4. Game Edukasi (games.index)
Route::get('/games', function () {
    return view('pages.game-index');
})->name('games.index');

Route::get('/game', function () {
    return redirect()->route('games.index');
})->name('game.index');


// 5. Peringkat / Leaderboard (leaderboard.index)
Route::get('/leaderboard', function () {
    $leaderboard = User::where('role', 'mahasiswa')
        ->orderBy('xp', 'desc')
        ->limit(10)
        ->get();
    return view('pages.peringkat-index', compact('leaderboard'));
})->name('leaderboard.index');

Route::get('/peringkat', function () {
    return redirect()->route('leaderboard.index');
})->name('peringkat.index');


// 6. Profil Pengguna, Progres Belajar, Riwayat Kuis — dilindungi middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/learning-progress', [LearningProgressController::class, 'index'])->name('learning-progress.index');
    Route::post('/learning-progress/{material}/complete', [LearningProgressController::class, 'markComplete'])->name('learning-progress.complete');
    Route::get('/quiz-history', [QuizHistoryController::class, 'index'])->name('quiz-history.index');
});

Route::get('/profil', function () {
    return redirect()->route('profile.index');
})->name('profil.index');


// =========================================================================
// Autentikasi & Persona Switcher untuk Pengujian Sprint 1
// =========================================================================
Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali di EnergiKita!');
    }

    return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.']);
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home')->with('info', 'Anda telah keluar dari akun.');
})->name('logout');

// Quick Demo Persona Switcher (Faris Dzakiy / Mahasiswa vs SRE Admin vs Guest)
Route::get('/demo-switch/{persona}', function ($persona) {
    if ($persona === 'mahasiswa') {
        $user = User::where('email', 'faris@student.ac.id')->first();
        if ($user) Auth::login($user);
        return redirect()->route('home')->with('success', 'Beralih ke persona: Faris Dzakiy (Mahasiswa, 85 XP)');
    } elseif ($persona === 'sre') {
        $user = User::where('email', 'admin@sre.org')->first();
        if ($user) Auth::login($user);
        return redirect()->route('home')->with('success', 'Beralih ke persona: SRE Indonesia (Admin, 450 XP)');
    } elseif ($persona === 'guest') {
        Auth::logout();
        return redirect()->route('home')->with('info', 'Beralih ke mode Pengunjung (Guest: 0 XP, Badge Energy Beginner)');
    }
    return redirect()->route('home');
})->name('demo.switch');


// =========================================================================
// Badge Pencapaian Belajar (Fitur Badge Achievement System)
// =========================================================================

// 7. Katalog Badge Publik (badges.index)
Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');

Route::get('/badge', function () {
    return redirect()->route('badges.index');
})->name('badge.index');


// =========================================================================
// Admin Dashboard & Pengelolaan Konten (F-05: PBI-013, PBI-014, PBI-015)
// Khusus untuk role Organisasi SRE ('sre' atau 'admin')
// =========================================================================
Route::middleware(['auth', 'role.sre'])->prefix('admin')->name('admin.')->group(function () {
    // 1. PBI-013 & PBI-014: Kelola Materi Pembelajaran
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/',              [MaterialAdminController::class, 'index'])->name('index');
        Route::get('/create',        [MaterialAdminController::class, 'create'])->name('create');
        Route::post('/',             [MaterialAdminController::class, 'store'])->name('store');
        Route::get('/{material}/edit', [MaterialAdminController::class, 'edit'])->name('edit');
        Route::put('/{material}',    [MaterialAdminController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialAdminController::class, 'destroy'])->name('destroy');

        // Shortcut ke kelola soal materi
        Route::get('/{material}/questions', [QuestionAdminController::class, 'indexByMaterial'])->name('questions');
    });

    // 2. PBI-015: Kelola Soal & Kunci Jawaban Kuis
    Route::prefix('quizzes/{quiz}/questions')->name('quizzes.questions.')->group(function () {
        Route::get('/',                [QuestionAdminController::class, 'index'])->name('index');
        Route::get('/create',          [QuestionAdminController::class, 'create'])->name('create');
        Route::post('/',               [QuestionAdminController::class, 'store'])->name('store');
        Route::get('/{question}/edit', [QuestionAdminController::class, 'edit'])->name('edit');
        Route::put('/{question}',      [QuestionAdminController::class, 'update'])->name('update');
        Route::delete('/{question}',   [QuestionAdminController::class, 'destroy'])->name('destroy');
    });

    // Endpoint CRUD soal kuis langsung
    Route::put('/questions/{question}', function (Request $request, \App\Models\Question $question) {
        $quiz = $question->quiz;
        return app(QuestionAdminController::class)->update($request, $quiz, $question);
    })->name('questions.update');

    Route::delete('/questions/{question}', function (Request $request, \App\Models\Question $question) {
        $quiz = $question->quiz;
        return app(QuestionAdminController::class)->destroy($request, $quiz, $question);
    })->name('questions.destroy');

    // 3. Admin CRUD Badge
    Route::prefix('badges')->name('badges.')->group(function () {
        Route::get('/',             [BadgeAdminController::class, 'index'])->name('index');
        Route::get('/create',       [BadgeAdminController::class, 'create'])->name('create');
        Route::post('/',            [BadgeAdminController::class, 'store'])->name('store');
        Route::get('/{badge}/edit', [BadgeAdminController::class, 'edit'])->name('edit');
        Route::put('/{badge}',      [BadgeAdminController::class, 'update'])->name('update');
        Route::delete('/{badge}',   [BadgeAdminController::class, 'destroy'])->name('destroy');
    });
});
