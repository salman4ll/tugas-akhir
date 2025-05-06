<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqProduk extends Model
{
    protected $table = 'tbl_faq_produk';

    public function kategori_produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
