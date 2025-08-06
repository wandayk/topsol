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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('creation_date');
            $table->date('launch_date')->nullable();
            $table->string('season')->nullable(); // Outono/Inverno, Primavera/Verão
            $table->integer('year');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->string('theme')->nullable(); // Tema da coleção
            $table->json('color_palette')->nullable(); // Cores principais da coleção
            $table->string('inspiration')->nullable(); // Inspiração da coleção
            $table->timestamps();
            
            $table->index(['status', 'year']);
            $table->index('season');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};