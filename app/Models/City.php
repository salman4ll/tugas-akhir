<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'm_kabupaten';

    public function province()
    {
        return $this->belongsTo(Province::class, 'm_provinsi_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'm_kabupaten_id');
    }
}
