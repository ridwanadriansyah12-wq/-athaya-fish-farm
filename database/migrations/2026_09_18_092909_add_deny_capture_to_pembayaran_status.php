<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan nilai 'deny' dan 'capture' ke enum status_pembayaran.
     * Midtrans dapat mengirimkan kedua status ini melalui webhook/API.
     */
    public function up(): void
    {
        // MySQL: ALTER TABLE untuk ubah enum
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM(
            'pending',
            'settlement',
            'capture',
            'expire',
            'failure',
            'cancel',
            'deny'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Rollback ke enum asal (tanpa deny dan capture)
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM(
            'pending',
            'settlement',
            'expire',
            'failure',
            'cancel'
        ) NOT NULL DEFAULT 'pending'");
    }
};
