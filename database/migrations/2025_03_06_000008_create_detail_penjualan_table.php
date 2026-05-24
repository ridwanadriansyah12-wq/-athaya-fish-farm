<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Penjualan');
            $table->unsignedBigInteger('ID_Bahan');
            $table->decimal('Harga', 12, 2);
            $table->integer('Qty');
            $table->foreign('ID_Penjualan')->references('id')->on('penjualan')->onDelete('cascade');
            $table->foreign('ID_Bahan')->references('id')->on('menu')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
