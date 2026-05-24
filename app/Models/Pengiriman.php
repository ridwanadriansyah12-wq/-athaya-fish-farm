<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengiriman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengiriman';
    
    protected $fillable = [
        'pesanan_id',
        'nomor_resi',
        'kurir',
        'status',
        'alamat_pengiriman',
        'nomor_telepon_penerima',
        'catatan_pengiriman',
        'tanggal_kirim',
        'tanggal_tiba_estimasi',
        'tanggal_diterima'
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
        'tanggal_tiba_estimasi' => 'datetime',
        'tanggal_diterima' => 'datetime'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
