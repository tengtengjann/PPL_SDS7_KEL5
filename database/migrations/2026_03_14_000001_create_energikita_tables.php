<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. materials (id, title, category, description, body, image)
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // e.g. Energi Surya, Energi Angin, Energi Air, Efisiensi Energi
            $table->text('description');
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 3. quizzes (id, material_id, title)
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();
        });

        // 4. questions (id, quiz_id, question, options, answer, explanation)
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->text('question');
            $table->json('options');
            $table->string('answer');
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        // 5. progress (id, user_id, material_id, status)
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->string('status')->default('in_progress'); // in_progress, completed
            $table->timestamps();
            $table->unique(['user_id', 'material_id']);
        });

        // 6. quiz_results (id, user_id, quiz_id, score)
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->integer('score');
            $table->timestamps();
        });

        // 7. missions (id, title, xp, criteria)
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('xp');
            $table->string('criteria');
            $table->timestamps();
        });

        // 8. achievements (id, name, xp_threshold)
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('xp_threshold');
            $table->string('badge_icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('missions');
        Schema::dropIfExists('quiz_results');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('materials');
    }
};

