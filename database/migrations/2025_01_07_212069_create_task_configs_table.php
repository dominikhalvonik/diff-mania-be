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
        Schema::create('task_configs', function (Blueprint $table) {
            $table->id();


            // Foreign key referencing tasks
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();

            // Level at which the task becomes available
            $table->integer('level_available')->default(1);

            // Foreign key for the previous task that must be fulfilled
            $table->foreignId('previous_task_id')
                ->nullable()
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_configs');
    }
};
