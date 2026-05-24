<?php

namespace Database\Seeders;

use App\Models\KatalogIkan;
use Illuminate\Database\Seeder;

class KatalogIkanSeeder extends Seeder
{
    public function run(): void
    {
        $katalog = [
            // Nila
            ['jenis_ikan_id' => 1, 'nama_produk' => 'Nila Segar 500g', 'harga_satuan' => 35000, 'stok' => 50, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 1, 'nama_produk' => 'Nila Segar 1kg', 'harga_satuan' => 65000, 'stok' => 40, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 1, 'nama_produk' => 'Nila Hidup (per ekor)', 'harga_satuan' => 15000, 'stok' => 150, 'berat_gram' => 100, 'tersedia' => true],
            
            // Lele
            ['jenis_ikan_id' => 2, 'nama_produk' => 'Lele Segar 500g', 'harga_satuan' => 40000, 'stok' => 60, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 2, 'nama_produk' => 'Lele Segar 1kg', 'harga_satuan' => 75000, 'stok' => 45, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 2, 'nama_produk' => 'Lele Hidup (per ekor)', 'harga_satuan' => 12000, 'stok' => 200, 'berat_gram' => 80, 'tersedia' => true],
            
            // Mas
            ['jenis_ikan_id' => 3, 'nama_produk' => 'Mas Segar 500g', 'harga_satuan' => 50000, 'stok' => 30, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 3, 'nama_produk' => 'Mas Segar 1kg', 'harga_satuan' => 95000, 'stok' => 25, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 3, 'nama_produk' => 'Mas Hidup (per ekor)', 'harga_satuan' => 20000, 'stok' => 80, 'berat_gram' => 150, 'tersedia' => true],
            
            // Mujair
            ['jenis_ikan_id' => 4, 'nama_produk' => 'Mujair Segar 500g', 'harga_satuan' => 30000, 'stok' => 70, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 4, 'nama_produk' => 'Mujair Segar 1kg', 'harga_satuan' => 55000, 'stok' => 55, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 4, 'nama_produk' => 'Mujair Hidup (per ekor)', 'harga_satuan' => 10000, 'stok' => 250, 'berat_gram' => 120, 'tersedia' => true],
            
            // Patin
            ['jenis_ikan_id' => 5, 'nama_produk' => 'Patin Segar 500g', 'harga_satuan' => 45000, 'stok' => 35, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 5, 'nama_produk' => 'Patin Segar 1kg', 'harga_satuan' => 85000, 'stok' => 28, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 5, 'nama_produk' => 'Patin Hidup (per ekor)', 'harga_satuan' => 18000, 'stok' => 100, 'berat_gram' => 140, 'tersedia' => true],
            
            // Gurame
            ['jenis_ikan_id' => 6, 'nama_produk' => 'Gurame Segar 500g', 'harga_satuan' => 65000, 'stok' => 20, 'berat_gram' => 500, 'tersedia' => true],
            ['jenis_ikan_id' => 6, 'nama_produk' => 'Gurame Segar 1kg', 'harga_satuan' => 125000, 'stok' => 15, 'berat_gram' => 1000, 'tersedia' => true],
            ['jenis_ikan_id' => 6, 'nama_produk' => 'Gurame Hidup (per ekor)', 'harga_satuan' => 35000, 'stok' => 40, 'berat_gram' => 200, 'tersedia' => true],
        ];

        foreach ($katalog as $item) {
            KatalogIkan::create($item);
        }
    }
}
