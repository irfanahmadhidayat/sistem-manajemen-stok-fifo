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

        // Buat transaksi keluar terlebih dahulu
        $barangKeluar = BarangKeluar::create([
            'nama_barang'       => $namaBarang,
            'tanggal_keluar'    => $request->tanggal_keluar,
            'jumlah_keluar'     => $jumlahKeluar,
            'kode_transaksi'    => $request->kode_transaksi,
        ]);

        // Ambil batch dengan sisa > 0 (FIFO)
        $batches = BarangMasuk::where('nama_barang', $namaBarang)
            ->where('sisa', '>', 0)
            ->orderBy('tanggal_masuk')
            ->get();

        $sisaKeluar = $jumlahKeluar;

        foreach ($batches as $batch) {
            if ($sisaKeluar <= 0) break;

            $ambil = min($batch->sisa, $sisaKeluar);

            // Kurangi sisa di batch
            $batch->sisa -= $ambil;
            $batch->save();

            // Simpan detail pengeluaran batch ini
            BarangKeluarDetail::create([
                'barang_keluar_id' => $barangKeluar->id,
                'barang_masuk_id'  => $batch->id,
                'jumlah_keluar'    => $ambil
            ]);

            $sisaKeluar -= $ambil;
        }

        if ($sisaKeluar > 0) {
            // Rollback perubahan sisa batch
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

        // Update total stok
        $barang->stok -= $jumlahKeluar;
        $barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang keluar berhasil dicatat dengan FIFO!',
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
    public function destroyBatch($kodeTransaksiKeluar)
    {
        // Ambil semua baris yang punya kode_transaksi_keluar sama
        $details = BarangKeluar::where('kode_transaksi', $kodeTransaksiKeluar)->get();

        if ($details->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        foreach ($details as $barangKeluar) {
            $jumlahKeluar = $barangKeluar->jumlah_keluar;
            $kodeTransaksiMasuk = $barangKeluar->kode_transaksi_masuk;
            $namaBarang = $barangKeluar->nama_barang;

            // Tambahkan kembali stok total barang
            $barang = Barang::where('nama_barang', $namaBarang)->first();
            if ($barang) {
                $barang->stok += $jumlahKeluar;
                $barang->save();
            }

            // Tambahkan kembali sisa pada batch masuk
            $barangMasuk = BarangMasuk::where('kode_transaksi', $kodeTransaksiMasuk)->first();
            if ($barangMasuk) {
                $barangMasuk->sisa += $jumlahKeluar;
                $barangMasuk->save();
            }

            // Hapus data keluar per baris
            $barangKeluar->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
