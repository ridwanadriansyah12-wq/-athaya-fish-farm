<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('Periode');
            $table->decimal('Total_Pendapatan', 12, 2);
            $table->decimal('Total_Pengeluaran', 12, 2);
            $table->decimal('Laba_Rugi', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};
