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

    protected $appends = ['first_image'];

    public function getGambarAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return [$value];
    }

    public function setGambarAttribute($value)
    {
        $this->attributes['gambar'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function getFirstImageAttribute()
    {
        $images = $this->gambar;
        return !empty($images) ? $images[0] : null;
    }

    public function jenisIkan()
    {
        return $this->belongsTo(JenisIkan::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
