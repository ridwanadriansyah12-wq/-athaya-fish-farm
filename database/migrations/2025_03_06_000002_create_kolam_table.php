<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolam', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kolam');
            $table->string('lokasi');
            $table->integer('kapasitas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kolam');
    }
};
