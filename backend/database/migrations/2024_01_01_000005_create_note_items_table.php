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
        Schema::create('note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Preço unitário no momento da venda
            $table->decimal('total_price', 10, 2); // Preço total (quantity * unit_price)
            $table->string('size')->nullable(); // Tamanho escolhido
            $table->string('color')->nullable(); // Cor escolhida
            $table->text('customization')->nullable(); // Personalizações
            $table->timestamps();
            
            $table->unique(['note_id', 'item_id', 'size', 'color']);
            $table->index('note_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_items');
    }
};