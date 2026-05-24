<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan';
    
    protected $fillable = [
        'nomor_pesanan',
        'customer_id',
        'status',
        'total_harga',
        'total_jasa_budidaya',
        'total_pembayaran',
        'catatan_pesanan',
        'alamat_pengiriman',
        'diterima_pemilik_at',
        'dikonfirmasi_at',
        'pembayaran_at',
        'dikirim_at',
        'selesai_at'
    ];

    protected $casts = [
        'diterima_pemilik_at' => 'datetime',
        'dikonfirmasi_at' => 'datetime',
        'pembayaran_at' => 'datetime',
        'dikirim_at' => 'datetime',
        'selesai_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::updated(function ($pesanan) {
            // Jika status berubah menjadi 'lunas' (dari bukan 'lunas') -> kurangi stok
            if ($pesanan->isDirty('status') && $pesanan->status === 'lunas' && $pesanan->getOriginal('status') !== 'lunas') {
                foreach ($pesanan->detailPesanan as $detail) {
                    $katalog = $detail->katalogIkan;
                    if ($katalog) {
                        $katalog->decrement('stok', $detail->kuantitas);
                    }
                }
            }

            // Jika status berubah dari 'lunas' ke 'batal' atau 'ditolak' -> kembalikan stok
            if ($pesanan->isDirty('status') && in_array($pesanan->status, ['batal', 'ditolak']) && $pesanan->getOriginal('status') === 'lunas') {
                foreach ($pesanan->detailPesanan as $detail) {
                    $katalog = $detail->katalogIkan;
                    if ($katalog) {
                        $katalog->increment('stok', $detail->kuantitas);
                    }
                }
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengemasan()
    {
        return $this->hasOne(Pengemasan::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
}
