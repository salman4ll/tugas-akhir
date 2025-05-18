<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingOrder extends Model
{
    protected $table = 'tbl_lacak_pesanan';

    protected $fillable = [
        'order_id',
        'status',
        'note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
