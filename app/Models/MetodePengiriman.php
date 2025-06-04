<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePengiriman extends Model
{
    protected $table = 'tbl_metode_pengiriman';
    protected $fillable = [
        'nama',
        'courier_name',
        'courier_code',
        'courier_service',
        'courier_service_code',
        'courier_service_name',
        'description',
        'shipping_type',
        'service_type',
        'created_at',
        'duration_estimate',
        'is_active',
        'updated_at',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'metode_pengiriman_id');
    }
}
