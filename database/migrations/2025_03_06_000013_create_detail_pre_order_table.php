<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pre_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Pre_Order_ID');
            $table->string('Nama_Item');
            $table->decimal('Harga_Satuan', 12, 2);
            $table->integer('Jumlah');
            $table->decimal('Subtotal', 12, 2);
            $table->foreign('Pre_Order_ID')->references('id')->on('pre_order')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pre_order');
    }
};
