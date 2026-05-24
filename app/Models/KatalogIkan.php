<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KatalogIkan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'katalog_ikan';
    
    protected $fillable = [
        'jenis_ikan_id',
        'nama_produk',
        'harga_satuan',
        'stok',
        'deskripsi',
        'gambar',
        'berat_gram',
        'tersedia'
    ];

    protected $casts = [
        'tersedia' => 'boolean'
    ];

    public function jenisIkan()
    {
        return $this->belongsTo(JenisIkan::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
