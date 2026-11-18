<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi — Tabel badges & user_badges
     * Fitur: Sistem Badge Pencapaian Belajar
     */
    public function up(): void
    {
        // Tabel katalog badge (definisi badge oleh admin)
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Nama badge, misal "Quiz Master"
            $table->text('description')->nullable(); // Deskripsi badge
            $table->string('icon')->default('🏅');   // Emoji atau nama ikon
            $table->string('criteria_type');          // Tipe kriteria: modules_completed, quizzes_passed, xp_reached, missions_completed
            $table->integer('criteria_value');         // Nilai ambang batas, misal 5 (modul), 100 (XP)
            $table->timestamps();
        });

        // Tabel pivot: badge yang sudah diraih user
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'badge_id']); // Setiap user hanya bisa mendapat 1 badge yang sama
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
