<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenawaranBudidaya extends Model
{
    use HasFactory;

    protected $table = 'penawaran_budidaya';

    protected $fillable = [
        'user_id',
        'jenis_ikan',
        'nomor_whatsapp',
        'jumlah_ikan',
        'foto',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'foto' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
