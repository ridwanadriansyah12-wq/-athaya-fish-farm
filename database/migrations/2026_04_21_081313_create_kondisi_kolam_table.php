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
        if (!Schema::hasTable('kondisi_kolam')) {
            Schema::create('kondisi_kolam', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                $table->date('tanggal_pencatatan');
                $table->integer('jumlah_ikan');
                $table->decimal('jumlah_pakan_gram', 10, 2);
                $table->decimal('suhu_air_celsius', 5, 2);
                $table->decimal('ph_air', 4, 2)->nullable();
                $table->decimal('oksigen_terlarut_ppm', 5, 2)->nullable();
                $table->string('kondisi_umum')->nullable();
                $table->text('catatan_khusus')->nullable();
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('kondisi_kolam', 'jenis_ikan_id')) {
            Schema::table('kondisi_kolam', function (Blueprint $table) {
                $table->foreignId('jenis_ikan_id')->nullable()->constrained('jenis_ikan')->onDelete('cascade');
                if (!Schema::hasColumn('kondisi_kolam', 'tanggal_pencatatan')) $table->date('tanggal_pencatatan');
                if (!Schema::hasColumn('kondisi_kolam', 'jumlah_ikan')) $table->integer('jumlah_ikan');
                if (!Schema::hasColumn('kondisi_kolam', 'jumlah_pakan_gram')) $table->decimal('jumlah_pakan_gram', 10, 2);
                if (!Schema::hasColumn('kondisi_kolam', 'suhu_air_celsius')) $table->decimal('suhu_air_celsius', 5, 2);
                if (!Schema::hasColumn('kondisi_kolam', 'ph_air')) $table->decimal('ph_air', 4, 2)->nullable();
                if (!Schema::hasColumn('kondisi_kolam', 'oksigen_terlarut_ppm')) $table->decimal('oksigen_terlarut_ppm', 5, 2)->nullable();
                if (!Schema::hasColumn('kondisi_kolam', 'kondisi_umum')) $table->string('kondisi_umum')->nullable();
                if (!Schema::hasColumn('kondisi_kolam', 'catatan_khusus')) $table->text('catatan_khusus')->nullable();
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
