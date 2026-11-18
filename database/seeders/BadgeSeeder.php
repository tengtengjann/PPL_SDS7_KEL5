<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Seed data badge awal untuk EnergiKita.
     */
    public function run(): void
    {
        $badges = [
            // Badge berbasis modul materi yang diselesaikan
            [
                'name'           => 'Penjelajah Pemula',
                'description'    => 'Menyelesaikan 1 modul materi energi pertama. Langkah awal menuju pemahaman energi bersih!',
                'icon'           => '🌱',
                'criteria_type'  => 'modules_completed',
                'criteria_value' => 1,
            ],
            [
                'name'           => 'Penjelajah Aktif',
                'description'    => 'Menyelesaikan 3 modul materi energi. Semangat belajar Anda mulai terlihat!',
                'icon'           => '📚',
                'criteria_type'  => 'modules_completed',
                'criteria_value' => 3,
            ],
            [
                'name'           => 'Ahli Energi',
                'description'    => 'Menyelesaikan 5 modul materi energi. Anda semakin mendalami ilmu energi terbarukan!',
                'icon'           => '🎓',
                'criteria_type'  => 'modules_completed',
                'criteria_value' => 5,
            ],
            [
                'name'           => 'Master Energi',
                'description'    => 'Menyelesaikan 10 modul materi. Pengetahuan energi Anda sudah luas dan mendalam!',
                'icon'           => '👑',
                'criteria_type'  => 'modules_completed',
                'criteria_value' => 10,
            ],

            // Badge berbasis kuis yang dilalui
            [
                'name'           => 'Quiz Starter',
                'description'    => 'Menyelesaikan kuis pertama Anda. Siap menguji pemahaman energi!',
                'icon'           => '✅',
                'criteria_type'  => 'quizzes_passed',
                'criteria_value' => 1,
            ],
            [
                'name'           => 'Quiz Champion',
                'description'    => 'Menyelesaikan 5 kuis dengan sukses. Anda benar-benar memahami materi!',
                'icon'           => '🏆',
                'criteria_type'  => 'quizzes_passed',
                'criteria_value' => 5,
            ],
            [
                'name'           => 'Quiz Legend',
                'description'    => 'Menyelesaikan 10 kuis. Legenda kuis EnergiKita!',
                'icon'           => '💎',
                'criteria_type'  => 'quizzes_passed',
                'criteria_value' => 10,
            ],

            // Badge berbasis XP
            [
                'name'           => 'Green Explorer',
                'description'    => 'Mengumpulkan 50 XP. Perjalanan hijau Anda dimulai!',
                'icon'           => '🍃',
                'criteria_type'  => 'xp_reached',
                'criteria_value' => 50,
            ],
            [
                'name'           => 'Eco Warrior',
                'description'    => 'Mengumpulkan 150 XP. Anda adalah pejuang lingkungan sejati!',
                'icon'           => '⚡',
                'criteria_type'  => 'xp_reached',
                'criteria_value' => 150,
            ],
            [
                'name'           => 'Energy Guardian',
                'description'    => 'Mengumpulkan 500 XP. Penjaga energi bersih masa depan!',
                'icon'           => '🛡️',
                'criteria_type'  => 'xp_reached',
                'criteria_value' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }

        // Otomatis berikan badge yang memenuhi syarat ke user demo
        $badgeController = app(\App\Http\Controllers\BadgeController::class);
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $badgeController->checkAndAwardBadge($user, 'modules_completed');
            $badgeController->checkAndAwardBadge($user, 'quizzes_passed');
            $badgeController->checkAndAwardBadge($user, 'xp_reached');
        }
    }
}
