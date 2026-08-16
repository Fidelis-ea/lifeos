<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date')->index();
            $table->tinyInteger('mood')->default(5); // 1-10
            $table->tinyInteger('energy')->default(5); // 1-10
            $table->decimal('sleep_hours', 4, 1)->default(7); // e.g. 7.5
            $table->tinyInteger('productivity')->default(5); // 1-10
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('coding_minutes')->default(0);
            $table->unsignedSmallInteger('learning_minutes')->default(0);
            $table->unsignedSmallInteger('exercise_minutes')->default(0);
            $table->unsignedSmallInteger('gaming_minutes')->default(0);
            $table->unsignedSmallInteger('japanese_minutes')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_entries');
    }
};
