<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('Tanggal_Penjualan');
            $table->foreignId('ID_User')->constrained('users')->onDelete('cascade');
            $table->decimal('Total_Pendapatan', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
