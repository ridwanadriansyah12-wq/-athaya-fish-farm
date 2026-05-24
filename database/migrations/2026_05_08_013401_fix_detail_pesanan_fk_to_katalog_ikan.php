<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop constraint lama yang merujuk ke tabel 'menu'
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropForeign('detail_pesanan_katalog_ikan_id_foreign');
        });

        // Buat constraint baru yang merujuk ke tabel 'katalog_ikan'
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreign('katalog_ikan_id')
                  ->references('id')
                  ->on('katalog_ikan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropForeign('detail_pesanan_katalog_ikan_id_foreign');
        });

        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreign('katalog_ikan_id')
                  ->references('id')
                  ->on('menu')
                  ->onDelete('cascade');
        });
    }
};
