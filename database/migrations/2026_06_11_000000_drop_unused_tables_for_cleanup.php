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
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('detail_pre_order');
        Schema::dropIfExists('pre_order');
        Schema::dropIfExists('detail_penjualan');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('laporan_keuangan');
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Permanent cleanup, no rollback logic needed.
    }
};
