<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran';
    
    protected $fillable = [
        'pesanan_id',
        'transaction_id',
        'order_id',
        'jumlah',
        'metode_pembayaran',
        'status_pembayaran',
        'midtrans_snap_token',
        'invoice_path',
        'dibayar_at'
    ];

    protected $casts = [
        'dibayar_at' => 'datetime'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
