<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpCustomer extends Model
{
    protected $table = 'tbl_cp_customer';

    protected $fillable = [
        'customer_id',
        'nama',
        'email',
        'no_telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
