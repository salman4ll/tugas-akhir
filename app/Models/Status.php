<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'tbl_status';
    protected $fillable = [
        'nama',
        'urutan',
        'created_at',
        'updated_at',
    ];

    public function riwayatStatusOrder()
    {
        return $this->hasMany(RiwayatStatusOrder::class, 'status_id');
    }
}
