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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('reference'); // Ex: Mar/2017
            $table->string('process'); // Ex: Folha de pagamento
            $table->decimal('earnings', 10, 2); // Proventos
            $table->decimal('deductions', 10, 2); // Descontos
            $table->decimal('net', 10, 2); // Líquido
            $table->decimal('inss_base', 10, 2); // Base INSS
            $table->decimal('irrf_base', 10, 2); // Base IRRF
            $table->decimal('fgts_base', 10, 2); // Base FGTS
            $table->decimal('fgts_deposited', 10, 2); // FGTS Depositado
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
