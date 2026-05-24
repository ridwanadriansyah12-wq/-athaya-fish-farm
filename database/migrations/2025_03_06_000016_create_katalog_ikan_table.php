<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katalog_ikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_ikan_id')->constrained('jenis_ikan')->onDelete('cascade');
            $table->string('nama_produk');
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('berat_gram')->nullable();
            $table->boolean('tersedia')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katalog_ikan');
    }
};
