<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\UserBadge;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil mahasiswa yang sedang login.
     * Data profil hanya diambil dari Auth::user() — bukan dari parameter request.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil badge yang sudah diraih user (eager load relasi badge)
        $earnedBadges = UserBadge::where('user_id', $user->id)
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();

        $totalBadges = Badge::count();

        return view('pages.profil-index', compact('user', 'earnedBadges', 'totalBadges'));
    }
}
