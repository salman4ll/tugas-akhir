<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbl_role'; // Pastikan nama tabel sesuai dengan yang ada di database

    protected $fillable = [
        'nama',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
