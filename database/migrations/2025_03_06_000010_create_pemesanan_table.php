<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'dikonfirmasi', 'ditolak', 'pembayaran', 'lunas', 'persiapan', 'dikirim', 'selesai', 'batal'])->default('pending');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('total_jasa_budidaya', 15, 2)->default(0);
            $table->decimal('total_pembayaran', 15, 2);
            $table->text('catatan_pesanan')->nullable();
            $table->timestamp('diterima_pemilik_at')->nullable();
            $table->timestamp('dikonfirmasi_at')->nullable();
            $table->timestamp('pembayaran_at')->nullable();
            $table->timestamp('dikirim_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
