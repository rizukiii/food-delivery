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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('otp',10)->nullable()->change();
            $table->double('delivery_charge')->default(10.0)->nullable()->change();
            $table->double('total_tax_amount')->default(10.0)->nullable()->change();
            $table->boolean('refund_requested')->default(false)->nullable()->change();
            $table->boolean('refunded')->default(false)->nullable()->change();
            $table->boolean('scheduled')->default(false)->nullable()->change();
            $table->integer('details_count')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
