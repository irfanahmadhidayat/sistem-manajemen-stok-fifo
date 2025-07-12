<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluarDetail extends Model
{
    protected $fillable = [
        'barang_keluar_id',
        'barang_masuk_id',
        'jumlah_keluar'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
