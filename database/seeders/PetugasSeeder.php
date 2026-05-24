<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create petugas user
        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas Kolam',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'nomor_telepon' => '082123456789',
                'alamat' => 'Jl. Kolam Ikan No. 1',
                'saldo' => 0
            ]
        );

        $this->command->info('Akun petugas berhasil dibuat!');
        $this->command->info('Email: petugas@example.com');
        $this->command->info('Password: petugas123');
    }
}
