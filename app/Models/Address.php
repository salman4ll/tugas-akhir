<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'tbl_alamat';

    protected $fillable = [
        'customer_id',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'latitude',
        'longitude',
        'rt',
        'rw',
        'alamat',
        'kode_pos',
    ];

    public function customer()
    {
        return $this->belongsTo(CpCustomer::class, 'customer_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinsi_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'kabupaten_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan_id');
    }

    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class, 'kelurahan_id');
    }
}
