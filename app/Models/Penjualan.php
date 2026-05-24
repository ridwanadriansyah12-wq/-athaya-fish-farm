<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $guarded = ['id'];
    protected $casts = [
        'Tanggal_Penjualan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'ID_Penjualan', 'id');
    }
}
