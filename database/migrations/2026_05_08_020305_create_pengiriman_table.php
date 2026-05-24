<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel pengiriman
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->string('nomor_resi')->nullable();
            $table->string('kurir')->nullable();
            $table->enum('status', ['dipersiapkan', 'dikirim', 'diterima'])->default('dipersiapkan');
            $table->text('alamat_pengiriman')->nullable();
            $table->string('nomor_telepon_penerima')->nullable();
            $table->text('catatan_pengiriman')->nullable();
            $table->timestamp('tanggal_kirim')->nullable();
            $table->timestamp('tanggal_tiba_estimasi')->nullable();
            $table->timestamp('tanggal_diterima')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel pengemasan
        Schema::create('pengemasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->enum('status', ['pending', 'dikemas', 'selesai'])->default('pending');
            $table->string('jenis_kemasan')->nullable();
            $table->text('catatan_pengemasan')->nullable();
            $table->timestamp('dikemas_tanggal')->nullable();
            $table->timestamp('dicek_tanggal')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengemasan');
        Schema::dropIfExists('pengiriman');
    }
};
