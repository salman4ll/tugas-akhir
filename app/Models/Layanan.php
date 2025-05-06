<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'tbl_layanan';

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }
}
