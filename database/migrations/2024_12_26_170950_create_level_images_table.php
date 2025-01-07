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
        Schema::create('level_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->string('image_name');
            $table->foreign('image_name')->references('name')->on('images')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_images');
    }
};
