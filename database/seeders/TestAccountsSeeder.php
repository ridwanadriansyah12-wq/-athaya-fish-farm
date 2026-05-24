<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Customer Account
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'foto_profil' => null,
            'saldo' => 0
        ]);

        // Create Admin Account
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'nomor_telepon' => '082345678901',
            'alamat' => 'Jl. Admin No. 456, Jakarta Selatan',
            'foto_profil' => null,
            'saldo' => 0
        ]);

        // Create Owner/Pemilik Account
        User::create([
            'name' => 'Pemilik Athaya',
            'email' => 'pemilik@example.com',
            'password' => bcrypt('password123'),
            'role' => 'pemilik',
            'nomor_telepon' => '083456789012',
            'alamat' => 'Jl. Pemilik No. 789, Jakarta Barat',
            'foto_profil' => null,
            'saldo' => 0
        ]);

        $this->command->info('Test accounts created successfully!');
        $this->command->info('Customer: customer@example.com | Password: password123');
        $this->command->info('Admin: admin@example.com | Password: password123');
        $this->command->info('Owner: pemilik@example.com | Password: password123');
    }
}
