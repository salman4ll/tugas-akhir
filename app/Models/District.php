<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'm_kecamatan';

    public function city()
    {
        return $this->belongsTo(City::class, 'm_kabupaten_id');
    }

    public function subDistricts()
    {
        return $this->hasMany(SubDistrict::class, 'm_kecamatan_id');
    }
}
