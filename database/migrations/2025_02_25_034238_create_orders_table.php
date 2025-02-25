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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('delivery_address_id');
            $table->double('order_amount');
            $table->enum('order_payment', ['cash', 'debit', 'qris'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->double('total_tax_amount')->default(10.0);
            $table->text('order_note');
            $table->double('delivery_charge')->default(10.0);
            $table->timestamp('schedule_at')->nullable();
            $table->string('otp',10);
            $table->boolean('refund_requested')->default(false);
            $table->boolean('refunded')->default(false);
            $table->boolean('scheduled')->default(false);
            $table->integer('details_count')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
