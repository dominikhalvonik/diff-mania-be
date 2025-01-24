<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_rewards', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->integer('day')->default(0);
            $table->boolean('opened')->default(false);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_rewards');
    }
};
