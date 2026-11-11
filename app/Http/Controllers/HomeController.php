<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Material;
use App\Models\Mission;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman Beranda EnergiKita (F-01: Sprint 1).
     * Memenuhi:
     * - PBI-001 (Pengenalan EnergiKita & SDG 7)
     * - PBI-002 (Ringkasan Kategori Energi: Energi Surya, Energi Angin, Energi Air, Hemat Energi)
     * - PBI-003 (Navigasi Fitur Utama & Kartu Akses Cepat)
     * - Section Bawah (Ringkasan Progres Belajar Pengguna)
     */
    public function index(Request $request)
    {
        // -------------------------------------------------------------
        // 1. DATA PENGENALAN ENERGIKITA & RINGKASAN EDUKASI SDG 7 (PBI-001)
        // -------------------------------------------------------------
        $platformInfo = [
            'name' => 'EnergiKita',
            'tagline' => 'Belajar Energi Bersih untuk Masa Depan',
            'subtitle' => 'Kenali energi surya, angin, air, dan cara hemat energi untuk mewujudkan masa depan nol-emisi.',
            'sdg7_title' => 'SDG 7: Energi Bersih dan Terjangkau',
            'sdg7_description' => 'Tujuan Pembangunan Berkelanjutan ke-7 (SDG 7) berkomitmen menjamin akses energi yang terjangkau, andal, berkelanjutan, dan modern bagi semua orang pada tahun 2030.',
            'sdg7_pillars' => [
                [
                    'target' => 'Target 7.1',
                    'title' => 'Akses Universal',
                    'desc' => 'Menjamin akses universal ke layanan energi yang terjangkau, andal, dan modern bagi seluruh lapisan masyarakat.',
                    'icon' => 'plug-circle',
                ],
                [
                    'target' => 'Target 7.2',
                    'title' => 'Energi Terbarukan',
                    'desc' => 'Meningkatkan secara signifikan pangsa energi baru dan terbarukan (EBT) dalam bauran energi global.',
                    'icon' => 'sun-wind',
                ],
                [
                    'target' => 'Target 7.3',
                    'title' => 'Efisiensi / Hemat Energi',
                    'desc' => 'Menggandakan laju perbaikan global dalam efisiensi energi dan konservasi pemakaian listrik harian.',
                    'icon' => 'bolt-slash',
                ],
            ],
            'stats' => [
                'total_materials' => Material::count(),
                'total_learners' => User::where('role', 'mahasiswa')->count(),
                'target_year' => '2030',
                'partner' => 'Organisasi SRE (Society of Renewable Energy)',
            ],
        ];

        // -------------------------------------------------------------
        // 2. DAFTAR KATEGORI ENERGI & JUMLAH MATERI PER KATEGORI (PBI-002)
        // Kategori: Energi Surya, Energi Angin, Energi Air, Hemat Energi
        // -------------------------------------------------------------
        $materialsCountByCategory = Material::query()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $categories = [
            [
                'slug' => 'energi-surya',
                'name' => 'Energi Surya',
                'tag' => 'Solar Energy',
                'color' => 'amber',
                'icon' => 'sun',
                'summary' => 'Pemanfaatan radiasi matahari melalui sistem fotovoltaik (PV) dan PLTS Atap untuk pasokan listrik ramah lingkungan tanpa emisi.',
                'highlights' => ['Fotovoltaik (PV)', 'PLTS Atap On-Grid', 'Net Metering'],
                'material_count' => $materialsCountByCategory['Energi Surya'] ?? 0,
            ],
            [
                'slug' => 'energi-angin',
                'name' => 'Energi Angin',
                'tag' => 'Wind Energy',
                'color' => 'sky',
                'icon' => 'wind',
                'summary' => 'Konversi energi kinetik hembusan angin menjadi tenaga mekanik rotor dan listrik menggunakan turbin angin modern.',
                'highlights' => ['Aerodinamika Rotor', 'PLTB Sidrap & Jeneponto', 'Offshore Wind'],
                'material_count' => $materialsCountByCategory['Energi Angin'] ?? 0,
            ],
            [
                'slug' => 'energi-air',
                'name' => 'Energi Air',
                'tag' => 'Hydro Energy',
                'color' => 'blue',
                'icon' => 'droplet',
                'summary' => 'Pembangkit ramah lingkungan memanfaatkan debit aliran dan tinggi jatuh air dari skala mikrohidro pedesaan hingga PLTA waduk.',
                'highlights' => ['Mikrohidro (PLTMH)', 'PLTA Run-of-River', 'Beban Puncak Peaker'],
                'material_count' => $materialsCountByCategory['Energi Air'] ?? 0,
            ],
            [
                'slug' => 'hemat-energi',
                'name' => 'Hemat Energi',
                'tag' => 'Energy Efficiency',
                'color' => 'emerald',
                'icon' => 'leaf',
                'summary' => 'Langkah cerdas mengoptimalkan konsumsi listrik harian, audit energi mandiri, serta efisiensi perangkat elektronik.',
                'highlights' => ['Konservasi Listrik', 'Smart Meter & IoT', 'Label Bintang 5'],
                'material_count' => ($materialsCountByCategory['Hemat Energi'] ?? 0) + ($materialsCountByCategory['Efisiensi Energi'] ?? 0),
            ],
        ];

        // -------------------------------------------------------------
        // 3. KARTU AKSES CEPAT FITUR UTAMA (PBI-003)
        // -------------------------------------------------------------
        $quickAccessCards = [
            [
                'id' => 'materi',
                'title' => 'Materi Energi',
                'subtitle' => 'Pelajari topik',
                'description' => 'Jelajahi modul belajar interaktif seputar energi surya, angin, air, hingga tips hemat energi harian.',
                'route' => 'materials.index',
                'badge' => $platformInfo['stats']['total_materials'] . ' Modul',
                'color' => 'emerald',
                'action_label' => 'Mulai Belajar',
            ],
            [
                'id' => 'kuis',
                'title' => 'Kuis Cepat',
                'subtitle' => 'Uji pemahaman',
                'description' => 'Asah pengetahuan Anda tentang transisi energi dan dapatkan skor instan serta penjelasan materi.',
                'route' => 'quizzes.index',
                'badge' => 'Tantangan Kilat',
                'color' => 'blue',
                'action_label' => 'Mulai Kuis',
            ],
            [
                'id' => 'misi',
                'title' => 'Misi Harian',
                'subtitle' => 'Kumpulkan XP',
                'description' => 'Selesaikan misi belajar harian untuk mengumpulkan XP dan membuka badge pencapaian baru.',
                'route' => 'missions.index',
                'badge' => '+75 XP Hari Ini',
                'color' => 'amber',
                'action_label' => 'Lihat Misi',
            ],
        ];

        // -------------------------------------------------------------
        // 4. RINGKASAN PROGRES BELAJAR PENGGUNA (Section Bawah Beranda)
        // -------------------------------------------------------------
        $totalMaterials = Material::count();
        $isLoggedIn = Auth::check();
        $currentUser = Auth::user();

        if ($isLoggedIn && $currentUser) {
            // Pengguna sedang login: Ambil data riil dari database
            $completedCount = Progress::where('user_id', $currentUser->id)
                ->where('status', 'completed')
                ->count();

            $inProgressCount = Progress::where('user_id', $currentUser->id)
                ->where('status', 'in_progress')
                ->count();

            $percentage = $totalMaterials > 0
                ? round(($completedCount / $totalMaterials) * 100)
                : 0;

            $xp = $currentUser->xp ?? 0;

            // Cari badge tertinggi yang sudah dicapai berdasarkan threshold XP
            $currentBadge = Achievement::where('xp_threshold', '<=', $xp)
                ->orderBy('xp_threshold', 'desc')
                ->first();

            $badgeName = $currentBadge ? $currentBadge->name : 'Energy Beginner';
            $badgeIcon = $currentBadge ? ($currentBadge->badge_icon ?? 'beginner') : 'beginner';

            // Target badge berikutnya
            $nextBadge = Achievement::where('xp_threshold', '>', $xp)
                ->orderBy('xp_threshold', 'asc')
                ->first();

            $xpNeeded = $nextBadge ? max(0, $nextBadge->xp_threshold - $xp) : 0;

            $userProgress = [
                'is_logged_in' => true,
                'is_guest' => false,
                'user' => $currentUser,
                'name' => $currentUser->name,
                'total_materials' => $totalMaterials,
                'completed_count' => $completedCount,
                'in_progress_count' => $inProgressCount,
                'percentage' => $percentage,
                'xp' => $xp,
                'badge' => $badgeName,
                'badge_icon' => $badgeIcon,
                'next_badge' => $nextBadge ? $nextBadge->name : null,
                'xp_needed' => $xpNeeded,
            ];
        } else {
            // Pengguna belum login (Guest): Tampilkan nilai default awal
            // Status badge awal adalah 'Energy Beginner' dan XP awal adalah 0
            $initialBadge = Achievement::orderBy('xp_threshold', 'asc')->first();
            $initialBadgeName = $initialBadge ? $initialBadge->name : 'Energy Beginner';

            $userProgress = [
                'is_logged_in' => false,
                'is_guest' => true,
                'user' => null,
                'name' => 'Pengunjung Baru',
                'total_materials' => $totalMaterials,
                'completed_count' => 0,
                'in_progress_count' => 0,
                'percentage' => 0,
                'xp' => 0,
                'badge' => $initialBadgeName,
                'badge_icon' => 'beginner',
                'next_badge' => 'Green Explorer',
                'xp_needed' => 50,
            ];
        }

        // Misi harian aktif untuk ditampilkan sekilas
        $dailyMissions = Mission::limit(3)->get();

        return view('home', compact(
            'platformInfo',
            'categories',
            'quickAccessCards',
            'userProgress',
            'dailyMissions'
        ));
    }
}
