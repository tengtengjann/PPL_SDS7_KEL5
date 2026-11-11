<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Material;
use App\Models\Mission;
use App\Models\Progress;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnergiKitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Achievements (Badges)
        $achievements = [
            [
                'name' => 'Energy Beginner',
                'xp_threshold' => 0,
                'badge_icon' => 'beginner',
                'description' => 'Memulai langkah awal menjelajahi dunia energi bersih dan terjangkau.'
            ],
            [
                'name' => 'Green Explorer',
                'xp_threshold' => 50,
                'badge_icon' => 'explorer',
                'description' => 'Mempelajari dasar-dasar energi baru terbarukan dan konsep SDG 7.'
            ],
            [
                'name' => 'Renewable Champion',
                'xp_threshold' => 150,
                'badge_icon' => 'champion',
                'description' => 'Menuntaskan berbagai materi pembangkit terbarukan dan lulus uji pemahaman.'
            ],
            [
                'name' => 'Net-Zero Hero',
                'xp_threshold' => 300,
                'badge_icon' => 'hero',
                'description' => 'Pemimpin aksi nyata transisi energi menuju masa depan berkelanjutan.'
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['name' => $ach['name']], $ach);
        }

        // 2. Materials
        $materialsData = [
            // Energi Surya
            [
                'title' => 'Pengenalan Panel Surya Fotovoltaik (PV)',
                'category' => 'Energi Surya',
                'description' => 'Memahami prinsip efek fotovoltaik dalam mengubah radiasi sinar matahari menjadi energi listrik bersih.',
                'body' => 'Panel surya atau photovoltaic (PV) adalah teknologi ramah lingkungan yang memanfaatkan sinar matahari secara langsung. Indonesia memiliki intensitas radiasi matahari rata-rata 4.8 kWh/m2 per hari yang sangat potensial untuk PLTS Rooftop skala rumah maupun skala utilitas komersial.',
                'image' => 'solar-pv.jpg',
            ],
            [
                'title' => 'PLTS Atap & Integrasi Smart Grid',
                'category' => 'Energi Surya',
                'description' => 'Bagaimana sistem On-Grid dan Net Metering memungkinkan rumah tangga memproduksi energi mandiri.',
                'body' => 'Dengan sistem PLTS Atap On-Grid, kelebihan energi listrik dapat dialirkan kembali ke jaringan listrik PLN dengan sistem meter ekspor-impor (net-metering). Hal ini menghemat tagihan listrik bulanan sekaligus mendukung pencapaian bauran EBT nasional.',
                'image' => 'solar-rooftop.jpg',
            ],

            // Energi Angin
            [
                'title' => 'Prinsip Kerja Turbin Angin Modern',
                'category' => 'Energi Angin',
                'description' => 'Mengubah energi kinetik aliran udara menjadi energi mekanik poros rotor dan listrik generator.',
                'body' => 'Turbin angin modern terdiri dari rotor tiga bilah, nacelle, generator, dan tower. Ketika angin mengalir melewati bilah, tercipta gaya angkat aerodinamis (aerodynamic lift) yang memutar poros utama generator guna memproduksi arus listrik.',
                'image' => 'wind-turbine.jpg',
            ],
            [
                'title' => 'Potensi PLTB di Indonesia: Studi Sidrap & Jeneponto',
                'category' => 'Energi Angin',
                'description' => 'Pemanfaatan potensi angin pesisir dan perbukitan Sulawesi Selatan sebagai lumbung energi terbarukan.',
                'body' => 'PLTB Sidrap (75 MW) dan PLTB Tolo Jeneponto (72 MW) adalah bukti nyata bahwa Indonesia mampu mengoptimalkan energi bayu. Proyek ini memasok daya ke sistem kelistrikan Sulawesi Selatan dengan emisi nol karbon selama operasi.',
                'image' => 'wind-farm.jpg',
            ],

            // Energi Air
            [
                'title' => 'Pembangkit Listrik Tenaga Mikrohidro (PLTMH)',
                'category' => 'Energi Air',
                'description' => 'Solusi listrik terpencil dan pedesaan dengan memanfaatkan debit aliran sungai kecil.',
                'body' => 'Mikrohidro adalah pembangkit listrik tenaga air skala kecil (hingga 100 kW) yang ramah lingkungan karena seringkali menggunakan sistem run-of-river tanpa perlu membangun bendungan raksasa yang merusak ekosistem bantaran sungai.',
                'image' => 'hydro-micro.jpg',
            ],
            [
                'title' => 'PLTA Skala Besar & Manajemen Waduk Berkelanjutan',
                'category' => 'Energi Air',
                'description' => 'Pemanfaatan head air dan waduk multiguna untuk stabilitas beban puncak kelistrikan.',
                'body' => 'PLTA memanfaatkan energi potensial air yang jatuh dari ketinggian tertentu (head). PLTA memegang peranan krusial sebagai pembangkit beban puncak (peaker) yang dapat merespons perubahan kebutuhan beban jaringan listrik dalam hitungan detik.',
                'image' => 'hydro-dam.jpg',
            ],

            // Hemat Energi
            [
                'title' => 'Audit Energi dan Konservasi Rumah Tangga',
                'category' => 'Hemat Energi',
                'description' => 'Strategi cerdas menghemat pemakaian listrik AC, lampu LED, dan perangkat elektronik.',
                'body' => 'Energi terbersih adalah energi yang tidak pernah kita gunakan secara sia-sia. Dengan melakukan audit energi mandiri, mengganti ke perangkat berlabel bintang 5 efisiensi energi, dan mematikan vampire loads, sebuah keluarga bisa memangkas emisi karbon dan biaya listrik hingga 30%.',
                'image' => 'energy-efficiency.jpg',
            ],
            [
                'title' => 'Teknologi Smart Meter & Manajemen Beban Puncak',
                'category' => 'Hemat Energi',
                'description' => 'Peran digitalisasi kelistrikan (IoT) dalam memantau dan mengoptimalkan penggunaan energi.',
                'body' => 'Advanced Metering Infrastructure (AMI) dan smart meter memberikan informasi konsumsi energi secara real-time ke konsumen. Hal ini mendorong perilaku hemat energi dan membantu penyeimbangan jaringan listrik saat beban puncak (peak load).',
                'image' => 'smart-meter.jpg',
            ],
        ];

        $createdMaterials = [];
        foreach ($materialsData as $m) {
            $createdMaterials[] = Material::updateOrCreate(
                ['title' => $m['title']],
                $m
            );
        }

        // 3. Missions
        $missions = [
            [
                'title' => 'Pelajari Modul Dasar PLTS Fotovoltaik',
                'xp' => 25,
                'criteria' => 'Selesaikan membaca materi modul Energi Surya dasar.',
            ],
            [
                'title' => 'Uji Pemahaman: Kuis Cepat Energi Angin',
                'xp' => 30,
                'criteria' => 'Jawab minimal 3 pertanyaan kuis turbin angin dengan benar.',
            ],
            [
                'title' => 'Terapkan 3 Aksi Hemat Energi Hari Ini',
                'xp' => 20,
                'criteria' => 'Centang checklist konservasi energi rumah tangga.',
            ],
        ];

        foreach ($missions as $mission) {
            Mission::updateOrCreate(['title' => $mission['title']], $mission);
        }

        // 4. Quizzes & Questions
        if (!empty($createdMaterials)) {
            $quiz1 = Quiz::updateOrCreate(
                ['material_id' => $createdMaterials[0]->id],
                ['title' => 'Kuis Kilat: Dasar Sel Surya & Fotovoltaik']
            );

            Question::updateOrCreate(
                ['quiz_id' => $quiz1->id, 'question' => 'Apa prinsip utama yang mengubah sinar matahari menjadi energi listrik pada panel surya?'],
                [
                    'options' => [
                        'A' => 'Efek Fotovoltaik',
                        'B' => 'Efek Termoelektrik',
                        'C' => 'Efek Elektromagnetik',
                        'D' => 'Induksi Elektrostatis'
                    ],
                    'answer' => 'A',
                    'explanation' => 'Efek fotovoltaik adalah fenomena fisika di mana semikonduktor menyerap foton cahaya dan melepaskan elektron bebas sehingga menghasilkan arus listrik.'
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $quiz1->id, 'question' => 'Tujuan utama SDG 7 adalah memastikan akses energi yang...'],
                [
                    'options' => [
                        'A' => 'Murah namun berbasis batubara',
                        'B' => 'Terjangkau, andal, berkelanjutan, dan modern bagi semua',
                        'C' => 'Hanya dikhususkan untuk kawasan industri',
                        'D' => 'Bergantung penuh pada impor bahan bakar minyak'
                    ],
                    'answer' => 'B',
                    'explanation' => 'SDG 7 PBB bertujuan menjamin akses energi yang terjangkau, andal, berkelanjutan, dan modern bagi semua umat manusia.'
                ]
            );
        }

        // 5. Demo Users
        // Mahasiswa (Faris Dzakiy)
        $student = User::updateOrCreate(
            ['email' => 'faris@student.ac.id'],
            [
                'name' => 'Faris Dzakiy',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'xp' => 85, // Level Green Explorer (threshold 50)
            ]
        );

        // Organisasi SRE Admin
        $sreAdmin = User::updateOrCreate(
            ['email' => 'admin@sre.org'],
            [
                'name' => 'Organisasi SRE Officer',
                'password' => Hash::make('password123'),
                'role' => 'sre',
                'xp' => 450, // Level Net-Zero Hero
            ]
        );

        // User Progress for Faris Dzakiy
        if (isset($createdMaterials[0])) {
            Progress::updateOrCreate(
                ['user_id' => $student->id, 'material_id' => $createdMaterials[0]->id],
                ['status' => 'completed']
            );
        }
        if (isset($createdMaterials[1])) {
            Progress::updateOrCreate(
                ['user_id' => $student->id, 'material_id' => $createdMaterials[1]->id],
                ['status' => 'completed']
            );
        }
        if (isset($createdMaterials[2])) {
            Progress::updateOrCreate(
                ['user_id' => $student->id, 'material_id' => $createdMaterials[2]->id],
                ['status' => 'in_progress']
            );
        }

        // Quiz Result demo
        if (isset($quiz1)) {
            QuizResult::updateOrCreate(
                ['user_id' => $student->id, 'quiz_id' => $quiz1->id],
                ['score' => 100]
            );
        }
    }
}

