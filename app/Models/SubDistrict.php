<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'm_kelurahan';

    public function district()
    {
        return $this->belongsTo(District::class, 'm_kecamatan_id');
    }
}
