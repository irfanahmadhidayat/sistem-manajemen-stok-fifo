<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'nama_barang',
        'tanggal_keluar',
        'tanggal_kadaluwarsa',
        'jumlah_keluar'
    ];
}
