<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPreOrder extends Model
{
    use HasFactory;

    protected $table = 'detail_pre_order';
    protected $guarded = ['id'];

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class, 'Pre_Order_ID', 'id');
    }
}
