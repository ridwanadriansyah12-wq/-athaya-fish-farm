<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengemasan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengemasan';
    
    protected $fillable = [
        'pesanan_id',
        'status',
        'jenis_kemasan',
        'catatan_pengemasan',
        'dikemas_tanggal',
        'dicek_tanggal'
    ];

    protected $casts = [
        'dikemas_tanggal' => 'datetime',
        'dicek_tanggal' => 'datetime'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
