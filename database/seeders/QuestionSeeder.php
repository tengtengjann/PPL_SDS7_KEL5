<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Seed soal pilihan ganda lengkap dengan kunci jawaban dan pembahasan.
     */
    public function run(): void
    {
        $materialSurya = Material::where('category', 'Energi Surya')->first();
        $materialAngin = Material::where('category', 'Energi Angin')->first();

        // 1. Kuis 1: Lengkapi 5 Soal pada Kuis Dasar Sel Surya & Fotovoltaik
        $quiz1 = Quiz::where('title', 'like', '%Dasar Sel Surya%')->first();
        if ($quiz1) {
            $quiz1AdditionalQuestions = [
                [
                    'question'    => 'Komponen elektronika daya yang berfungsi mengubah arus DC dari panel surya menjadi arus AC untuk peralatan rumah adalah...',
                    'options'     => [
                        'A' => 'Inverter',
                        'B' => 'Transformator Step-Up',
                        'C' => 'Kondensator Bank',
                        'D' => 'Rectifier (Penyearah)',
                    ],
                    'answer'      => 'A',
                    'explanation' => 'Inverter adalah perangkat krusial pada sistem PLTS yang bertugas mengonversi listrik arus searah (DC) hasil pembangkitan panel surya menjadi listrik arus bolak-balik (AC) dengan frekuensi dan tegangan yang sesuai dengan peralatan rumah tangga dan jaringan listrik PLN.',
                ],
                [
                    'question'    => 'Berapa rata-rata intensitas radiasi matahari harian di sebagian besar wilayah Indonesia?',
                    'options'     => [
                        'A' => '1.5 kWh/m2 per hari',
                        'B' => '2.8 kWh/m2 per hari',
                        'C' => '4.8 kWh/m2 per hari',
                        'D' => '8.5 kWh/m2 per hari',
                    ],
                    'answer'      => 'C',
                    'explanation' => 'Sebagai negara khatulistiwa, Indonesia memiliki potensi radiasi matahari yang tinggi dan merata sepanjang tahun, yakni berkisar 4.8 kWh/m2 per hari dengan variasi bulanan yang relatif rendah, menjadikannya ideal untuk pemanfaatan PLTS.',
                ],
                [
                    'question'    => 'Bahan semikonduktor yang paling banyak digunakan sebagai bahan baku sel surya fotovoltaik komersial saat ini adalah...',
                    'options'     => [
                        'A' => 'Tembaga (Copper)',
                        'B' => 'Silikon (Silicon)',
                        'C' => 'Aluminium (Aluminum)',
                        'D' => 'Karbon Aktif',
                    ],
                    'answer'      => 'B',
                    'explanation' => 'Silikon (Si) adalah bahan semikonduktor paling dominan (lebih dari 90% pasar) dalam industri sel surya fotovoltaik karena kelimpahannya di bumi, stabilitas tinggi, serta efisiensi konversi yang telah teruji dalam varian monokristalin dan polikristalin.',
                ],
            ];

            foreach ($quiz1AdditionalQuestions as $q) {
                Question::updateOrCreate(
                    [
                        'quiz_id'  => $quiz1->id,
                        'question' => $q['question'],
                    ],
                    [
                        'options'     => $q['options'],
                        'answer'      => $q['answer'],
                        'explanation' => $q['explanation'],
                    ]
                );
            }
        }

        // 2. Kuis 2: 5 Soal Lengkap Kuis Kilat Energi Terbarukan Indonesia
        $quiz2 = Quiz::updateOrCreate(
            ['title' => 'Kuis Kilat: Energi Terbarukan Indonesia'],
            ['material_id' => $materialAngin?->id ?? $materialSurya?->id ?? 1]
        );

        $quiz2Questions = [
            [
                'question'    => 'Apa sumber energi utama yang dimanfaatkan oleh panel surya fotovoltaik?',
                'options'     => [
                    'A' => 'Radiasi sinar matahari',
                    'B' => 'Panas bumi (geotermal)',
                    'C' => 'Energi kinetik angin',
                    'D' => 'Energi potensial air',
                ],
                'answer'      => 'A',
                'explanation' => 'Panel surya fotovoltaik bekerja dengan menyerap radiasi sinar matahari. Foton cahaya menumbuk sel semikonduktor (biasanya silikon) sehingga elektron terlepas dan menghasilkan arus listrik searah (DC). Proses ini disebut efek fotovoltaik.',
            ],
            [
                'question'    => 'PLTS Rooftop dengan sistem On-Grid memanfaatkan mekanisme apa agar kelebihan listrik bisa dialirkan ke jaringan PLN?',
                'options'     => [
                    'A' => 'Battery Storage System',
                    'B' => 'Net Metering (Ekspor-Impor)',
                    'C' => 'Off-Grid Inverter',
                    'D' => 'Diesel Generator Backup',
                ],
                'answer'      => 'B',
                'explanation' => 'Sistem Net Metering memungkinkan pemilik PLTS Atap On-Grid mengekspor kelebihan energi listrik ke jaringan PLN. Meter listrik mencatat selisih daya ekspor dan impor sehingga tagihan bulanan bisa berkurang signifikan. Ini berbeda dengan sistem Off-Grid yang sepenuhnya mandiri.',
            ],
            [
                'question'    => 'Berapa kapasitas PLTB (Pembangkit Listrik Tenaga Bayu) pertama Indonesia di Sidrap, Sulawesi Selatan?',
                'options'     => [
                    'A' => '30 MW',
                    'B' => '75 MW',
                    'C' => '150 MW',
                    'D' => '200 MW',
                ],
                'answer'      => 'B',
                'explanation' => 'PLTB Sidrap di Kabupaten Sidenreng Rappang, Sulawesi Selatan memiliki kapasitas terpasang 75 MW. Diresmikan pada 2018, PLTB ini terdiri dari 30 turbin angin dan merupakan pembangkit listrik tenaga bayu skala komersial pertama di Indonesia.',
            ],
            [
                'question'    => 'Pembangkit Listrik Tenaga Mikrohidro (PLTMH) biasanya memiliki kapasitas maksimal berapa?',
                'options'     => [
                    'A' => '10 kW',
                    'B' => '100 kW',
                    'C' => '1 MW',
                    'D' => '10 MW',
                ],
                'answer'      => 'B',
                'explanation' => 'PLTMH (Pembangkit Listrik Tenaga Mikrohidro) adalah pembangkit skala kecil dengan kapasitas hingga 100 kW. Teknologi ini cocok untuk elektrifikasi daerah terpencil dan pedesaan karena memanfaatkan aliran sungai kecil tanpa perlu membangun bendungan besar.',
            ],
            [
                'question'    => 'Langkah konservasi energi rumah tangga yang paling efektif untuk mengurangi vampire loads adalah...',
                'options'     => [
                    'A' => 'Mengganti lampu TL dengan lampu pijar',
                    'B' => 'Menyalakan AC 24 jam dengan suhu 16°C',
                    'C' => 'Mencabut charger dan perangkat elektronik dari stop kontak saat tidak digunakan',
                    'D' => 'Menggunakan genset diesel sebagai cadangan listrik',
                ],
                'answer'      => 'C',
                'explanation' => 'Vampire loads (standby power) adalah konsumsi listrik oleh perangkat elektronik yang tetap terpasang di stop kontak meski dalam keadaan mati atau standby. Mencabut charger, TV, dan perangkat lainnya saat tidak digunakan dapat menghemat 5-10% konsumsi listrik rumah tangga.',
            ],
        ];

        foreach ($quiz2Questions as $q) {
            Question::updateOrCreate(
                [
                    'quiz_id'  => $quiz2->id,
                    'question' => $q['question'],
                ],
                [
                    'options'     => $q['options'],
                    'answer'      => $q['answer'],
                    'explanation' => $q['explanation'],
                ]
            );
        }
    }
}
