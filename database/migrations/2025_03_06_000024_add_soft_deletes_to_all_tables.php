<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'kematian_ikan',
            'laporan',
            'layanan_budidaya',
            'pembayaran',
            'kondisi_kolam',
            'pengemasan',
            'pengiriman',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'kematian_ikan',
            'laporan',
            'layanan_budidaya',
            'pembayaran',
            'kondisi_kolam',
            'pengemasan',
            'pengiriman',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
