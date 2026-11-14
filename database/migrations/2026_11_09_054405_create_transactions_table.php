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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // No Nota/Invoice
            $table->decimal('total_amount', 12, 2); // Total harga
            $table->decimal('cash_amount', 12, 2);   // Uang tunai dari pembeli
            $table->decimal('change_amount', 12, 2); // Kembalian
            $table->foreignId('user_id')->constrained(); // Kasir yang bertugas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
