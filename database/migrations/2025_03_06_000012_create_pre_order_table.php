<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_User')->constrained('users')->onDelete('cascade');
            $table->string('Nama_Pemesan');
            $table->dateTime('Waktu_Pengambilan');
            $table->decimal('Total_Harga', 12, 2);
            $table->string('Status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_order');
    }
};
