<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class LaporanBarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $namaBarangDipilih = $request->get('nama_barang');
        $namaBarangs = BarangMasuk::select('nama_barang')->distinct()->pluck('nama_barang');

        if ($namaBarangDipilih) {
            // Jika filter nama_barang
            $batchMasuk = BarangMasuk::where('nama_barang', $namaBarangDipilih)
                ->orderBy('tanggal_masuk')
                ->get();
        } else {
            // Jika Semua Barang
            $batchMasuk = BarangMasuk::orderBy('tanggal_masuk')->get();
        }

        $totalMasuk = $batchMasuk->sum('jumlah_masuk');

        return view('layouts-page.laporan-barang-masuk.index', compact(
            'namaBarangDipilih',
            'namaBarangs',
            'batchMasuk',
            'totalMasuk',
        ));
    }

    public function print(Request $request)
    {
        $namaBarangDipilih = $request->get('nama_barang');

        $batchMasuk = $namaBarangDipilih
            ? BarangMasuk::where('nama_barang', $namaBarangDipilih)->orderBy('tanggal_masuk')->get()
            : BarangMasuk::orderBy('tanggal_masuk')->get();

        $totalMasuk = $batchMasuk->sum('jumlah_masuk');

        $dompdf = new Dompdf();
        $html = view('layouts-page.laporan-barang-masuk.print', compact(
            'namaBarangDipilih',
            'batchMasuk',
            'totalMasuk',
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan-barang-masuk.pdf', ['Attachment' => false]);
    }
}
