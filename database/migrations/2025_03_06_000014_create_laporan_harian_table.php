<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('id_kolam')->constrained('kolam')->onDelete('cascade');
            $table->foreignId('id_ikan')->constrained('jenis_ikan')->onDelete('cascade');
            $table->foreignId('id_pakan')->constrained('pakan')->onDelete('cascade');
            $table->integer('jumlah_kematian')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};
