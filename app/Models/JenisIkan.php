<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisIkan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_ikan';
    protected $guarded = ['id'];
    
    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'id_ikan', 'id');
    }
}
