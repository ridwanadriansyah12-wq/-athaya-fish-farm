<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('Nama_Produk');
            $table->decimal('Harga', 12, 2);
            $table->integer('Stok');
            $table->string('Kategori');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
