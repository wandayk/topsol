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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Valor do pagamento
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'pix', 'bank_transfer']);
            $table->date('payment_date'); // Data do pagamento
            $table->date('due_date')->nullable(); // Data de vencimento (para parcelas)
            $table->integer('installment_number')->default(1); // Número da parcela
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->string('transaction_id')->nullable(); // ID da transação (PIX, cartão, etc)
            $table->text('notes')->nullable(); // Observações sobre o pagamento
            $table->timestamps();
            
            $table->index(['note_id', 'status']);
            $table->index('payment_date');
            $table->index('due_date');
            $table->index('installment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};