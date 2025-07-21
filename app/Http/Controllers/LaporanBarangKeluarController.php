<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class LaporanBarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $namaBarangDipilih = $request->get('nama_barang');
        $namaBarangs = BarangKeluar::select('nama_barang')->distinct()->pluck('nama_barang');

        if ($namaBarangDipilih) {
            $batchKeluar = BarangKeluar::where('nama_barang', $namaBarangDipilih)
                ->orderBy('tanggal_keluar')
                ->get();
        } else {
            $batchKeluar = BarangKeluar::orderBy('tanggal_keluar')->get();
        }

        $totalKeluar = $batchKeluar->sum('jumlah_keluar');

        return view('layouts-page.laporan-barang-keluar.index', compact(
            'namaBarangDipilih',
            'namaBarangs',
            'batchKeluar',
            'totalKeluar'
        ));
    }

    public function print(Request $request)
    {
        $namaBarangDipilih = $request->get('nama_barang');

        $batchKeluar = $namaBarangDipilih
            ? BarangKeluar::where('nama_barang', $namaBarangDipilih)->orderBy('tanggal_keluar')->get()
            : BarangKeluar::orderBy('tanggal_keluar')->get();

        $totalKeluar = $batchKeluar->sum('jumlah_keluar');

        $dompdf = new Dompdf();
        $html = view('layouts-page.laporan-barang-keluar.print', compact(
            'namaBarangDipilih',
            'batchKeluar',
            'totalKeluar'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan-barang-keluar.pdf', ['Attachment' => false]);
    }
}
