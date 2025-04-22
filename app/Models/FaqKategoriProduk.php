<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqKategoriProduk extends Model
{
    protected $table = 'tbl_faq_kategori_produk';

    public function kategori_produk()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }
}
