<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Test Customer Account
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@athaya.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'email_verified_at' => now(),
        ]);

        // Test Pemilik Account
        User::create([
            'name' => 'Pak Athaya (Owner)',
            'email' => 'pemilik@athaya.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
            'status' => 'active',
            'nomor_telepon' => '082345678901',
            'alamat' => 'Jl. Budidaya No. 456, Bekasi',
            'email_verified_at' => now(),
        ]);

        // Test Admin Account
        User::create([
            'name' => 'Admin Athaya',
            'email' => 'admin@athaya.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'nomor_telepon' => '083456789012',
            'alamat' => 'Jl. Admin No. 789, Bandung',
            'email_verified_at' => now(),
        ]);
    }
}
