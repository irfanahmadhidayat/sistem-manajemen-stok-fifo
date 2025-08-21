<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\BarangKeluarDetail;
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
            'tanggal_keluar'    => 'required',
            'jumlah_keluar'     => 'required',
        ], [
            'nama_barang.required' => 'Form Nama Barang Wajib Di Isi!',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi!',
            'jumlah_keluar.required' => 'Jumlah stok keluar wajib diisi!',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jumlahKeluar = $request->jumlah_keluar;
        $namaBarang = $request->nama_barang;

        $barang = Barang::where('nama_barang', $namaBarang)->first();
        if (!$barang || $barang->stok < $jumlahKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Total stok tidak mencukupi!'
            ], 400);
        }

        $barangKeluar = BarangKeluar::create([
            'nama_barang'       => $namaBarang,
            'tanggal_keluar'    => $request->tanggal_keluar,
            'jumlah_keluar'     => $jumlahKeluar,
            'kode_transaksi'    => $request->kode_transaksi,
        ]);

        $batches = BarangMasuk::where('nama_barang', $namaBarang)
            ->where('sisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->get();

        $sisaKeluar = $jumlahKeluar;

        foreach ($batches as $batch) {
            if ($sisaKeluar <= 0) break;

            $ambil = min($batch->sisa, $sisaKeluar);

            $batch->sisa -= $ambil;
            $batch->save();

            BarangKeluarDetail::create([
                'barang_keluar_id' => $barangKeluar->id,
                'barang_masuk_id'  => $batch->id,
                'jumlah_keluar'    => $ambil
            ]);

            $sisaKeluar -= $ambil;
        }

        if ($sisaKeluar > 0) {
            foreach ($barangKeluar->details as $detail) {
                $batch = BarangMasuk::find($detail->barang_masuk_id);
                $batch->sisa += $detail->jumlah_keluar;
                $batch->save();
            }

            $barangKeluar->details()->delete();
            $barangKeluar->delete();

            return response()->json([
                'success' => false,
                'message' => 'Stok batch tidak mencukupi!'
            ], 400);
        }

        $barang->stok -= $jumlahKeluar;
        $barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data' => $barangKeluar->load('details.barangMasuk')
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
        // 1. Ambil semua detail batch yang pernah dipakai
        $details = $barangKeluar->details;

        // 2. Kembalikan stok per-batch
        foreach ($details as $detail) {
            $batch = BarangMasuk::find($detail->barang_masuk_id);
            if ($batch) {
                $batch->sisa += $detail->jumlah_keluar;
                $batch->save();
            }
        }

        // 3. Kembalikan stok utama barang
        $barang = Barang::where('nama_barang', $barangKeluar->nama_barang)->first();
        if ($barang) {
            $barang->stok += $barangKeluar->jumlah_keluar;
            $barang->save();
        }

        // 4. Hapus detail dan data utama
        $barangKeluar->details()->delete();
        $barangKeluar->delete();

        // 5. Return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
