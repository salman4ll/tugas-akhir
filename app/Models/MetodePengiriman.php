<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePengiriman extends Model
{
    protected $table = 'tbl_metode_pengiriman';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'metode_pengiriman_id');
    }
}
