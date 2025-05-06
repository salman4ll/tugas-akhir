<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    protected $table = 'tbl_perangkat';
    
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'perangkat_id');
    }
}
