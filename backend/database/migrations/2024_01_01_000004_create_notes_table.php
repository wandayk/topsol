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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('note_number')->unique(); // Número da nota
            $table->foreignId('client_id')->constrained('clients');
            $table->decimal('subtotal', 12, 2)->default(0); // Subtotal dos itens
            $table->decimal('discount', 12, 2)->default(0); // Desconto aplicado
            $table->decimal('shipping', 12, 2)->default(0); // Frete
            $table->decimal('total_amount', 12, 2)->default(0); // Valor total
            $table->decimal('total_paid', 12, 2)->default(0); // Total já pago
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'pix', 'bank_transfer', 'installments']);
            $table->boolean('is_installment')->default(false);
            $table->integer('installment_count')->default(1); // Número de parcelas
            $table->enum('status', ['draft', 'confirmed', 'paid', 'cancelled'])->default('draft');
            $table->date('due_date')->nullable(); // Data de vencimento
            $table->date('delivery_date')->nullable(); // Data de entrega
            $table->text('notes')->nullable(); // Observações
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index('payment_method');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};