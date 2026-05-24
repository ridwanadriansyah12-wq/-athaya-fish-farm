<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan';
    protected $guarded = ['id'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'ID_Pemesanan', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'Menu_ID', 'id');
    }
}
