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
        Schema::create('user_attributes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_attribute_definition_id')->constrained()->onDelete('cascade');
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attributes');
    }
};
