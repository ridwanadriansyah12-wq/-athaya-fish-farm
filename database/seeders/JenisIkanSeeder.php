<?php

namespace Database\Seeders;

use App\Models\JenisIkan;
use Illuminate\Database\Seeder;

class JenisIkanSeeder extends Seeder
{
    public function run(): void
    {
        $jenisIkan = [
            [
                'nama_jenis' => 'Ikan Nila',
                'deskripsi' => 'Ikan air tawar yang populer dengan daging putih lembut dan bergizi tinggi',
                'gambar' => 'nila.jpg',
                'waktu_panen_hari' => 120,
            ],
            [
                'nama_jenis' => 'Ikan Lele',
                'deskripsi' => 'Ikan catfish dengan pertumbuhan cepat dan mudah dibudidayakan',
                'gambar' => 'lele.jpg',
                'waktu_panen_hari' => 90,
            ],
            [
                'nama_jenis' => 'Ikan Mas',
                'deskripsi' => 'Ikan air tawar yang berkualitas dengan rasa lezat dan tekstur daging empuk',
                'gambar' => 'mas.jpg',
                'waktu_panen_hari' => 150,
            ],
            [
                'nama_jenis' => 'Ikan Mujair',
                'deskripsi' => 'Ikan tilapia dengan harga terjangkau dan nilai gizi yang tinggi',
                'gambar' => 'mujair.jpg',
                'waktu_panen_hari' => 100,
            ],
            [
                'nama_jenis' => 'Ikan Patin',
                'deskripsi' => 'Ikan air tawar dengan daging tebal dan pertumbuhan yang menguntungkan',
                'gambar' => 'patin.jpg',
                'waktu_panen_hari' => 110,
            ],
            [
                'nama_jenis' => 'Ikan Gurame',
                'deskripsi' => 'Ikan premium dengan daging putih berkualitas tinggi dan flavor istimewa',
                'gambar' => 'gurame.jpg',
                'waktu_panen_hari' => 180,
            ],
        ];

        foreach ($jenisIkan as $jenis) {
            JenisIkan::create($jenis);
        }
    }
}
