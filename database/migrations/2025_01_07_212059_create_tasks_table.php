<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');
            $table->integer('requested_amount');
            $table->foreignId('user_attribute_definition_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('booster_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('reward_id')->constrained()->onDelete('cascade');


            $table->timestamps();
        });
        // Create a contraint to have at least booster_id or user_attribute_definition_id not null
        DB::statement('ALTER TABLE tasks ADD CONSTRAINT chk_tasks_booster_or_user_attr CHECK (booster_id IS NOT NULL OR user_attribute_definition_id IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE tasks DROP CONSTRAINT chk_tasks_booster_or_user_attr');
        Schema::dropIfExists('tasks');
    }
};
