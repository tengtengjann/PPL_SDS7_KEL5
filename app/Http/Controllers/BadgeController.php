<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Progress;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    /**
     * Halaman Katalog Badge — Menampilkan semua badge beserta status pencapaian user.
     */
    public function index()
    {
        $badges = Badge::orderBy('criteria_value', 'asc')->get();

        // Badge yang sudah diraih user yang sedang login
        $earnedBadgeIds = [];
        $earnedBadges = collect();

        if (Auth::check()) {
            $user = Auth::user();
            foreach (['modules_completed', 'quizzes_passed', 'xp_reached'] as $type) {
                $this->checkAndAwardBadge($user, $type);
            }

            $earnedBadges = UserBadge::where('user_id', Auth::id())
                ->with('badge')
                ->get()
                ->keyBy('badge_id');

            $earnedBadgeIds = $earnedBadges->keys()->toArray();
        }

        // Hitung progress user untuk setiap criteria_type
        $userStats = $this->getUserStats(Auth::user());

        return view('pages.badge-index', compact('badges', 'earnedBadgeIds', 'earnedBadges', 'userStats'));
    }

    /**
     * Cek dan berikan badge secara otomatis setelah aktivitas tertentu.
     *
     * Panggil method ini setelah user menyelesaikan modul/kuis/misi, contoh:
     *   app(BadgeController::class)->checkAndAwardBadge($user, 'modules_completed');
     *   app(BadgeController::class)->checkAndAwardBadge($user, 'quizzes_passed');
     *   app(BadgeController::class)->checkAndAwardBadge($user, 'xp_reached');
     *   app(BadgeController::class)->checkAndAwardBadge($user, 'missions_completed');
     *
     * @param  User   $user         User yang akan dicek pencapaiannya.
     * @param  string $activityType Tipe aktivitas: modules_completed, quizzes_passed, xp_reached, missions_completed.
     * @return array  Daftar badge baru yang diberikan (bisa kosong).
     */
    public function checkAndAwardBadge(User $user, string $activityType): array
    {
        $stats = $this->getUserStats($user);
        $currentValue = $stats[$activityType] ?? 0;

        // Ambil semua badge dengan criteria_type yang sesuai
        $eligibleBadges = Badge::where('criteria_type', $activityType)
            ->where('criteria_value', '<=', $currentValue)
            ->get();

        // Ambil badge yang sudah dimiliki user
        $ownedBadgeIds = UserBadge::where('user_id', $user->id)
            ->pluck('badge_id')
            ->toArray();

        $newlyAwarded = [];

        foreach ($eligibleBadges as $badge) {
            if (!in_array($badge->id, $ownedBadgeIds)) {
                UserBadge::create([
                    'user_id'   => $user->id,
                    'badge_id'  => $badge->id,
                    'earned_at' => now(),
                ]);

                $newlyAwarded[] = $badge;
            }
        }

        return $newlyAwarded;
    }

    /**
     * Hitung statistik aktivitas user untuk pencocokan kriteria badge.
     */
    private function getUserStats(?User $user): array
    {
        if (!$user) {
            return [
                'modules_completed'  => 0,
                'quizzes_passed'     => 0,
                'xp_reached'         => 0,
                'missions_completed' => 0,
            ];
        }

        return [
            'modules_completed'  => Progress::where('user_id', $user->id)
                                        ->where('status', 'completed')
                                        ->count(),
            'quizzes_passed'     => QuizResult::where('user_id', $user->id)->count(),
            'xp_reached'         => $user->xp ?? 0,
            'missions_completed' => 0, // Implementasikan sesuai logika tracking misi
        ];
    }
}
