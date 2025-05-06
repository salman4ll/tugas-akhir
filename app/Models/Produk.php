<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'tbl_produk';

    public function perangkat()
    {
        return $this->hasMany(Perangkat::class, 'produk_id');
    }

    public function faq_produk()
    {
        return $this->hasMany(FaqProduk::class, 'produk_id');
    }
}
