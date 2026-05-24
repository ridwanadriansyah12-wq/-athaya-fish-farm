<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('order_id')->unique();
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'settlement', 'expire', 'failure', 'cancel'])->default('pending');
            $table->text('midtrans_snap_token')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamp('dibayar_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
