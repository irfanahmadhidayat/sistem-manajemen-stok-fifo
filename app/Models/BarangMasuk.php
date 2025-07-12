<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'nama_barang',
        'tanggal_masuk',
        'tanggal_kadaluwarsa',
        'jumlah_masuk',
        'sisa'
    ];

    public function keluarDetails()
    {
        return $this->hasMany(BarangKeluarDetail::class);
    }
}
