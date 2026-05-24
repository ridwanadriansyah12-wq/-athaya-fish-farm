<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'ID_Penjualan', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'ID_Bahan', 'id');
    }
}
