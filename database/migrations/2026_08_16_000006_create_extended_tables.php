<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category'); // Programming, Design, Language, etc.
            $table->integer('current_level')->default(0); // 0-100% progress scale
            $table->integer('target_level')->default(100);
            $table->integer('learning_hours')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Skill Progress History
        Schema::create('skill_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->integer('level');
            $table->date('logged_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Learning Logs
        Schema::create('learning_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->string('topic');
            $table->integer('duration_minutes');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('in_progress'); // not_started, in_progress, completed, archived
            $table->integer('progress')->default(0); // 0-100
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('tech_stack')->nullable(); // comma-separated or json
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Project Tasks
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // Transactions (Finance Tracker)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // income, expense
            $table->decimal('amount', 12, 2);
            $table->string('category'); // Food, Transport, Education, Gaming, Entertainment, Internet, Shopping, Other
            $table->string('description')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // Achievements
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('🏆');
            $table->string('requirement_type'); // coding_hours, habit_streak, checkin_count, project_count, etc.
            $table->integer('requirement_value');
            $table->timestamps();
        });

        // User Achievements
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->date('unlocked_at');
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('learning_logs');
        Schema::dropIfExists('skill_progress');
        Schema::dropIfExists('skills');
    }
};
