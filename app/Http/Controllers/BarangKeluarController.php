<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts-page.barang-keluar.index', [
            'barangs'           => Barang::all(),
            'barangKeluar'      => BarangKeluar::all()
        ]);
    }

    public function getDataBarangKeluar()
    {
        return response()->json([
            'success'   => true,
            'data'      => BarangKeluar::all()
        ]);
    }

    public function getAutoCompleteData(Request $request)
    {
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if ($barang) {
            return response()->json([
                'nama_barang'   => $barang->nama_barang,
                'stok'          => $barang->stok,
                'satuan_id'     => $barang->satuan_id,
            ]);
        }
    }

    /**
     * Create Autocomplete Data In Update Method
     */

    public function getStok(Request $request)
    {
        $namaBarang = $request->input('nama_barang');
        $barang = Barang::where('nama_barang', $namaBarang)->select('stok', 'satuan_id')->first();

        $response = [
            'stok'          => $barang->stok,
            'satuan_id'     => $barang->satuan_id
        ];

        return response()->json($response);
    }

    public function getSatuan()
    {
        $satuans = Satuan::all();

        return response()->json($satuans);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts-page.barang-keluar.create', [
            'barangs' => Barang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang'       => 'required',
            'tanggal_keluar'     => 'required',
            'tanggal_kadaluwarsa' => 'required',
            'jumlah_keluar'      => 'required',
        ], [
            'nama_barang.required'      => 'Form Nama Barang Wajib Di Isi !',
            'tanggal_keluar.required'    => 'Pilih Barang Terlebih Dahulu !',
            'tanggal_kadaluwarsa.required' => 'Pilih Barang Terlebih Dahulu !',
            'jumlah_keluar.required'     => 'Form Jumlah Stok Keluar Wajib Di Isi !'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangKeluar = BarangKeluar::create([
            'nama_barang'       => $request->nama_barang,
            'tanggal_keluar'     => $request->tanggal_keluar,
            'tanggal_kadaluwarsa' => $request->tanggal_kadaluwarsa,
            'jumlah_keluar'      => $request->jumlah_keluar,
            'kode_transaksi'    => $request->kode_transaksi
        ]);

        if ($barangKeluar) {
            $barang = Barang::where('nama_barang', $request->nama_barang)->first();
            if ($barang) {
                $barang->stok -= $request->jumlah_keluar;
                $barang->save();
            }
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $barangKeluar
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        $jumlahKeluar = $barangKeluar->jumlah_keluar;
        $barangKeluar->delete();

        $barang = Barang::where('nama_barang', $barangKeluar->nama_barang)->first();
        if ($barang) {
            $barang->stok += $jumlahKeluar;
            $barang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
