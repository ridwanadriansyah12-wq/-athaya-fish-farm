<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    
    protected $fillable = [
        'pesanan_id',
        'katalog_ikan_id',
        'kuantitas',
        'harga_satuan',
        'subtotal',
        'dengan_layanan_budidaya',
        'durasi_budidaya_hari',
        'biaya_budidaya'
    ];

    protected $casts = [
        'dengan_layanan_budidaya' => 'boolean'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function katalogIkan()
    {
        return $this->belongsTo(KatalogIkan::class);
    }

    public function layananBudidaya()
    {
        return $this->hasOne(LayananBudidaya::class);
    }
}
