<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts-page.barang-masuk.index', [
            'barangs'      => Barang::all(),
            'barangsMasuk' => BarangMasuk::all()
        ]);
    }

    public function getDataBarangMasuk()
    {
        return response()->json([
            'success'   => true,
            'data'      => BarangMasuk::all()
        ]);
    }

    public function getAutoCompleteData(Request $request)
    {
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();;
        if ($barang) {
            return response()->json([
                'nama_barang'   => $barang->nama_barang,
                'stok'          => $barang->stok,
                'satuan_id'     => $barang->satuan_id,
            ]);
        }
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
        return view('layouts-page.barang-masuk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang'       => 'required',
            'tanggal_masuk'     => 'required',
            'tanggal_kadaluwarsa' => 'required',
            'jumlah_masuk'      => 'required',
        ], [
            'nama_barang.required'      => 'Form Nama Barang Wajib Di Isi !',
            'tanggal_masuk.required'    => 'Masukkan Tanggal Masuk Terlebih Dahulu !',
            'tanggal_kadaluwarsa.required' => 'Masukkan Tanggal Kadaluwarsa Terlebih Dahulu !',
            'jumlah_masuk.required'     => 'Form Jumlah Stok Masuk Wajib Di Isi !'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::create([
            'nama_barang'       => $request->nama_barang,
            'tanggal_masuk'     => $request->tanggal_masuk,
            'tanggal_kadaluwarsa' => $request->tanggal_kadaluwarsa,
            'jumlah_masuk'      => $request->jumlah_masuk,
            'sisa' => $request->jumlah_masuk,
            'kode_transaksi'    => $request->kode_transaksi
        ]);

        if ($barangMasuk) {
            $barang = Barang::where('nama_barang', $request->nama_barang)->first();
            if ($barang) {
                $barang->stok += $request->jumlah_masuk;
                $barang->save();
            }
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $barangMasuk
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
    public function destroy(BarangMasuk $barangMasuk)
    {
        $jumlahMasuk = $barangMasuk->jumlah_masuk;
        $barangMasuk->delete();

        $barang = Barang::where('nama_barang', $barangMasuk->nama_barang)->first();
        if ($barang) {
            $barang->stok -= $jumlahMasuk;
            $barang->save();
        }

        BarangMasuk::where('nama_barang', $barangMasuk->nama_barang)
            ->update(['sisa' => $barang->stok]);

        return response()->json([
            'success' => true,
            'message' => 'Data Barang Berhasil Dihapus!'
        ]);
    }
}
