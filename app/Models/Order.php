<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_order';

    protected $fillable = [
        'customer_id',
        'layanan_id',
        'perangkat_id',
        'alamat_customer_id',
        'cp_customer_id',
        'quantity',
        'order_date',
        'total_harga',
        'tanggal_pembayaran',
        'riwayat_status_order_id',
        'unique_order',
        'snap_token',
        'payment_status',
        'payment_url',
        'payment_method',
        'sn_kit',
        'sid',
        'is_ttd',
        'nama_node',
        'alamat_node',
        'jenis_pengiriman',
        'metode_pengiriman_id',
        'biaya_pengiriman',
        'ppn',
        'pph',
        'metode_pembayaran',
        'reff_id_ship',
        'nomor_resi',
        'tracking_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }

    public function alamatCustomer()
    {
        return $this->belongsTo(Address::class, 'alamat_customer_id');
    }

    public function cpCustomer()
    {
        return $this->belongsTo(CpCustomer::class, 'cp_customer_id');
    }

    public function riwayatStatusOrder()
    {
        return $this->hasMany(RiwayatStatusOrder::class, 'order_id');
    }

    public function statusTerakhir()
    {
        return $this->hasOne(RiwayatStatusOrder::class)->latestOfMany();
    }

    public function metodePengiriman()
    {
        return $this->belongsTo(MetodePengiriman::class, 'metode_pengiriman_id');
    }

    public function trackingOrder()
    {
        return $this->hasMany(TrackingOrder::class, 'order_id');
    }
}
