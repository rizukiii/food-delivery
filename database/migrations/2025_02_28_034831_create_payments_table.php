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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'expire'])->default('pending');
            $table->double('gross_amount');
            $table->timestamp('transaction_time')->nullable();
            $table->string('payment_code')->nullable(); // Untuk metode tertentu (e.g. QRIS, VA)
            $table->string('payment_url')->nullable(); // Untuk redirect user ke Midtrans
            $table->timestamps();
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
