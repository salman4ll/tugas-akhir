<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'tbl_kategori_produk';

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_produk_id');
    }

    public function faq_product()
    {
        return $this->hasMany(FaqKategoriProduk::class, 'kategori_produk_id');
    }
}
