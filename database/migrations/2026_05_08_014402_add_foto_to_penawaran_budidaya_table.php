<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penawaran_budidaya', function (Blueprint $table) {
            // Simpan array path foto sebagai JSON, nullable agar data lama tetap valid
            $table->json('foto')->nullable()->after('jumlah_ikan');
            $table->text('deskripsi')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('penawaran_budidaya', function (Blueprint $table) {
            $table->dropColumn(['foto', 'deskripsi']);
        });
    }
};
