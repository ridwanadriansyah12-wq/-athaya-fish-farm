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
        // Create laporan table if it doesn't exist
        if (!Schema::hasTable('laporan')) {
            Schema::create('laporan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                $table->string('periode_laporan');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->integer('jumlah_ikan_awal');
                $table->integer('jumlah_ikan_mati');
                $table->integer('jumlah_ikan_akhir');
                $table->decimal('total_pakan_digunakan', 12, 2)->nullable();
                $table->decimal('rata_rata_suhu', 5, 2)->nullable();
                $table->text('ringkasan')->nullable();
                $table->string('file_pdf')->nullable();
                $table->string('file_excel')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // Add columns if they don't exist
            Schema::table('laporan', function (Blueprint $table) {
                if (!Schema::hasColumn('laporan', 'jenis_ikan_id')) {
                    $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                }
                if (!Schema::hasColumn('laporan', 'periode_laporan')) {
                    $table->string('periode_laporan');
                }
                if (!Schema::hasColumn('laporan', 'tanggal_mulai')) {
                    $table->date('tanggal_mulai');
                }
                if (!Schema::hasColumn('laporan', 'tanggal_selesai')) {
                    $table->date('tanggal_selesai');
                }
                if (!Schema::hasColumn('laporan', 'jumlah_ikan_awal')) {
                    $table->integer('jumlah_ikan_awal');
                }
                if (!Schema::hasColumn('laporan', 'jumlah_ikan_mati')) {
                    $table->integer('jumlah_ikan_mati');
                }
                if (!Schema::hasColumn('laporan', 'jumlah_ikan_akhir')) {
                    $table->integer('jumlah_ikan_akhir');
                }
                if (!Schema::hasColumn('laporan', 'total_pakan_digunakan')) {
                    $table->decimal('total_pakan_digunakan', 12, 2)->nullable();
                }
                if (!Schema::hasColumn('laporan', 'rata_rata_suhu')) {
                    $table->decimal('rata_rata_suhu', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('laporan', 'ringkasan')) {
                    $table->text('ringkasan')->nullable();
                }
                if (!Schema::hasColumn('laporan', 'file_pdf')) {
                    $table->string('file_pdf')->nullable();
                }
                if (!Schema::hasColumn('laporan', 'file_excel')) {
                    $table->string('file_excel')->nullable();
                }
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
