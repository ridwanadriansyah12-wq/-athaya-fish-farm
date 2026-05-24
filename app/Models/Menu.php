<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $guarded = ['id'];

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'id_produk', 'id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'ID_Bahan', 'id');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'Menu_ID', 'id');
    }
}
