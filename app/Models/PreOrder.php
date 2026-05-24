<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $table = 'pre_order';
    protected $guarded = ['id'];
    protected $casts = [
        'Waktu_Pengambilan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    public function detailPreOrder()
    {
        return $this->hasMany(DetailPreOrder::class, 'Pre_Order_ID', 'id');
    }
}
