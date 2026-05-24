<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('katalog_ikan_id')->constrained('menu')->onDelete('cascade');
            $table->integer('kuantitas');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->boolean('dengan_layanan_budidaya')->default(false);
            $table->integer('durasi_budidaya_hari')->nullable();
            $table->decimal('biaya_budidaya', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
