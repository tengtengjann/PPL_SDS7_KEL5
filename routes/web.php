<?php

use App\Http\Controllers\HomeController;
use App\Models\Material;
use App\Models\Mission;
use App\Models\Quiz;
use App\Models\User;
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

// 1. Belajar / Materi Energi (materials.index)
Route::prefix('materials')->name('materials.')->group(function () {
    Route::get('/', function () {
        $materials = Material::all();
        $categories = Material::select('category')->distinct()->pluck('category');
        return view('pages.materi-index', compact('materials', 'categories'));
    })->name('index');

    Route::get('/category/{category}', function ($category) {
        $categoryName = str_replace('-', ' ', ucwords($category, '-'));
        $materials = Material::where('category', 'like', "%{$categoryName}%")->get();
        return view('pages.materi-category', compact('materials', 'categoryName'));
    })->name('category');

    Route::get('/{id}', function ($id) {
        $material = Material::with('quizzes')->findOrFail($id);
        return view('pages.materi-detail', compact('material'));
    })->name('show');
});

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


// 2. Kuis Cepat (quizzes.index)
Route::prefix('quizzes')->name('quizzes.')->group(function () {
    Route::get('/', function () {
        $quizzes = Quiz::with('material', 'questions')->get();
        return view('pages.kuis-index', compact('quizzes'));
    })->name('index');
});
Route::get('/kuis', function () {
    return redirect()->route('quizzes.index');
})->name('kuis.index');


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


// 6. Profil Pengguna (profile.index)
Route::get('/profile', function () {
    $user = Auth::user() ?? User::where('email', 'faris@student.ac.id')->first();
    return view('pages.profil-index', compact('user'));
})->name('profile.index');

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
