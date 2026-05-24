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
        if (!Schema::hasTable('kematian_ikan')) {
            Schema::create('kematian_ikan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kondisi_kolam_id')->nullable()->constrained('kondisi_kolam')->onDelete('cascade');
                $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                $table->integer('jumlah_mati');
                $table->string('penyebab_dugaan');
                $table->string('penyakit')->nullable();
                $table->text('deskripsi_gejala')->nullable();
                $table->text('tindakan_penanganan')->nullable();
                $table->dateTime('tanggal_kejadian');
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('kematian_ikan', 'kondisi_kolam_id')) {
            Schema::table('kematian_ikan', function (Blueprint $table) {
                $table->foreignId('kondisi_kolam_id')->nullable()->constrained('kondisi_kolam')->onDelete('cascade');
                if (!Schema::hasColumn('kematian_ikan', 'jenis_ikan_id')) $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                if (!Schema::hasColumn('kematian_ikan', 'jumlah_mati')) $table->integer('jumlah_mati');
                if (!Schema::hasColumn('kematian_ikan', 'penyebab_dugaan')) $table->string('penyebab_dugaan');
                if (!Schema::hasColumn('kematian_ikan', 'penyakit')) $table->string('penyakit')->nullable();
                if (!Schema::hasColumn('kematian_ikan', 'deskripsi_gejala')) $table->text('deskripsi_gejala')->nullable();
                if (!Schema::hasColumn('kematian_ikan', 'tindakan_penanganan')) $table->text('tindakan_penanganan')->nullable();
                if (!Schema::hasColumn('kematian_ikan', 'tanggal_kejadian')) $table->dateTime('tanggal_kejadian');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
