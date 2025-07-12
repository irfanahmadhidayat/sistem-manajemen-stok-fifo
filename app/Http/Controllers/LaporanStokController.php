<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index()
    {
        return view('layouts-page.laporan-stok.index');
    }

    public function getData(Request $request)
    {
        $selectedOption = $request->input('opsi');

        if ($selectedOption == 'semua') {
            $barangs = Barang::all();
        } elseif ($selectedOption == 'stok-habis') {
            $barangs = Barang::where('stok', 0)->get();
        } else {
            $barangs = Barang::all();
        }

        return response()->json($barangs);
    }

    public function printStok(Request $request)
    {
        $selectedOption = $request->input('opsi');

        if ($selectedOption == 'semua') {
            $barangs = Barang::all();
        } elseif ($selectedOption == 'stok-habis') {
            $barangs = Barang::where('stok', 0)->get();
        } else {
            $barangs = Barang::all();
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $html = view('/layouts-page/laporan-stok/print-stok', compact('barangs', 'selectedOption'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-stok.pdf', ['Attachment' => false]);
    }

    public function getSatuan()
    {
        $satuans = Satuan::all();

        return response()->json($satuans);
    }
}
