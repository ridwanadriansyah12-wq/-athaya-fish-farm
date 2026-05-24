<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananBudidaya extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layanan_budidaya';
    
    protected $fillable = [
        'detail_pesanan_id',
        'status',
        'tanggal_mulai',
        'tanggal_target_panen',
        'jumlah_pakan_harian',
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_target_panen' => 'date'
    ];

    public function detailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class);
    }
}
