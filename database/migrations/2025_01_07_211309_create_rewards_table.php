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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_attribute_definition_id')->constrained()->onDelete('cascade');
            $table->integer('amount');

            $table->timestamps();

            // Create a check to have the combination of user_attribute_definition_id and amount unique
            $table->unique(['user_attribute_definition_id', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
