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
        Schema::table('users', function (Blueprint $table) {
            // Add role column
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->after('password');
            }
            
            // Add nomor_telepon column
            if (!Schema::hasColumn('users', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable()->after('role');
            }
            
            // Add alamat column
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('nomor_telepon');
            }
            
            // Add foto_profil column
            if (!Schema::hasColumn('users', 'foto_profil')) {
                $table->string('foto_profil')->nullable()->after('alamat');
            }
            
            // Add saldo column
            if (!Schema::hasColumn('users', 'saldo')) {
                $table->decimal('saldo', 15, 2)->default(0)->after('foto_profil');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'nomor_telepon')) {
                $table->dropColumn('nomor_telepon');
            }
            if (Schema::hasColumn('users', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('users', 'foto_profil')) {
                $table->dropColumn('foto_profil');
            }
            if (Schema::hasColumn('users', 'saldo')) {
                $table->dropColumn('saldo');
            }
        });
    }
};
