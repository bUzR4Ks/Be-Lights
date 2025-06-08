<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_code')->unique()->nullable();
        $table->string('customer_name');         // Nama pembeli (user input saja)
        $table->enum('section', ['nasi_uduk', 'aneka_semur']);
        $table->integer('total_price');          // Total harga (sum of order_items)
        $table->enum('payment_status', ['pending','accepted','denied'])->default('pending');
        $table->enum('order_status', ['pending','in_progress','completed'])->default('pending');
        $table->string('payment_proof_path')->nullable(); // Path upload bukti
        $table->timestamps();
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
