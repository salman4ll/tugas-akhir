<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusOrder extends Model
{
    protected $table = 'tbl_riwayat_status_order';
    protected $fillable = [
        'order_id',
        'status_id',
        'keterangan',
        'tanggal',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
