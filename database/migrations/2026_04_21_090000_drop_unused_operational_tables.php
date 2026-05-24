<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks to safely drop tables
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('pengemasan');
        Schema::dropIfExists('kolam');
        Schema::dropIfExists('laporan_harian');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate minimal tables in case of rollback
        Schema::create('kolam', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kolam')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('pengemasan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->timestamps();
        });

        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
            $table->integer('jumlah_kematian')->default(0);
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });
    }
};
