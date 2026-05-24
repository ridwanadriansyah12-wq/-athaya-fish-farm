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
        Schema::dropIfExists('pakan');
        Schema::dropIfExists('kondisi_kolam');
        Schema::dropIfExists('kematian_ikan');
        Schema::dropIfExists('laporan');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed for dropped tables that are permanently removed
    }
};
