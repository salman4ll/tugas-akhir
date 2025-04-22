<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'tbl_layanan';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
