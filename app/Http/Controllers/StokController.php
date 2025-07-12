<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Models\BarangKeluarDetail;

class StokController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua nama barang unik
        $namaBarangs = BarangMasuk::select('nama_barang')->distinct()->pluck('nama_barang');

        // Jika filter barang dipilih
        $namaBarangDipilih = $request->get('nama_barang');

        $batchMasuk = collect();
        $batchKeluar = collect();

        if ($namaBarangDipilih) {
            $batchMasuk = BarangMasuk::where('nama_barang', $namaBarangDipilih)
                ->orderBy('tanggal_masuk')
                ->get();

            $batchKeluar = BarangKeluarDetail::with(['barangMasuk', 'barangKeluar'])
                ->whereHas('barangMasuk', function ($q) use ($namaBarangDipilih) {
                    $q->where('nama_barang', $namaBarangDipilih);
                })
                ->orderBy('created_at')
                ->get();
        }

        $totalMasuk = $batchMasuk->sum('jumlah_masuk');
        $totalSisa = $batchMasuk->sum('sisa');
        $totalKeluar = $batchKeluar->sum('jumlah_keluar');

        return view('layouts-page.stok-penjualan.index', compact(
            'namaBarangs',
            'namaBarangDipilih',
            'batchMasuk',
            'batchKeluar',
            'totalMasuk',
            'totalSisa',
            'totalKeluar'
        ));
    }
}
