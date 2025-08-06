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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique()->nullable(); // Código do produto
            $table->enum('category', ['top', 'bottom', 'dress', 'accessory', 'shoes', 'underwear']);
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable(); // Custo de produção
            $table->json('sizes')->nullable(); // Tamanhos disponíveis
            $table->json('colors')->nullable(); // Cores disponíveis
            $table->string('material')->nullable(); // Material principal
            $table->text('care_instructions')->nullable(); // Instruções de cuidado
            $table->json('reference_images')->nullable(); // URLs das imagens de referência
            $table->json('photoshoot_images')->nullable(); // URLs das imagens do ensaio
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(0); // Estoque mínimo
            $table->enum('status', ['draft', 'active', 'discontinued'])->default('draft');
            $table->timestamps();
            
            $table->index(['collection_id', 'status']);
            $table->index('category');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};