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
            $table->foreignId('appointment_id')->constrained('appointments')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->unsignedInteger('amount');
            $table->enum('payment_method', ['tunai', 'qris', 'transfer'])->default('tunai');
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
            $table->timestamp('paid_at')->nullable();
            $table->string('cashier_name')->nullable();
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
