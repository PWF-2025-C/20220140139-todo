<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Relasi ke users
    $table->string('title');
    $table->boolean('is_done')->default(false);
    $table->boolean('is_complete')->default(false);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
