<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('Personal'); // Coding, Learning, Health, etc.
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->integer('progress')->default(0); // 0-100
            $table->string('status')->default('not_started'); // not_started, in_progress, completed, archived
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('icon')->default('🎯');
            $table->string('color')->default('#5BC0EB');
            $table->timestamps();
        });

        Schema::create('goal_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->boolean('completed')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_tasks');
        Schema::dropIfExists('goals');
    }
};
