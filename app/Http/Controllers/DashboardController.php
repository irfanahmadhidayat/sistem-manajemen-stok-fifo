<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $stokHabis = Barang::where('stok', 0)->count();
        $stokMinimum = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        $tanggalSekarang = Carbon::today();
        $barangKadaluwarsa = BarangMasuk::whereDate('tanggal_kadaluwarsa', '<=', $tanggalSekarang->copy()->addMonth())->count();
        $recentBarang = Barang::latest()->take(5)->get();

        // Query Barang Masuk
        $barangMasukPerBulan = DB::table('barang_masuks')
            ->select(
                DB::raw('MONTH(tanggal_masuk) as bulan'),
                DB::raw('YEAR(tanggal_masuk) as tahun'),
                DB::raw('SUM(jumlah_masuk) as total')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Query Barang Keluar
        $barangKeluarPerBulan = DB::table('barang_keluars')
            ->select(
                DB::raw('MONTH(tanggal_keluar) as bulan'),
                DB::raw('YEAR(tanggal_keluar) as tahun'),
                DB::raw('SUM(jumlah_keluar) as total')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Siapkan 3 bulan terakhir
        $labels = [];
        $masukData = [];
        $keluarData = [];

        $now = Carbon::now()->startOfMonth();
        for ($i = 2; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $bulan = $date->month;
            $tahun = $date->year;

            $labels[] = $date->format('F Y');

            // Cari data Barang Masuk
            $foundMasuk = $barangMasukPerBulan->first(function ($item) use ($bulan, $tahun) {
                return intval($item->bulan) === $bulan && intval($item->tahun) === $tahun;
            });
            $masukData[] = $foundMasuk ? $foundMasuk->total : 0;

            // Cari data Barang Keluar
            $foundKeluar = $barangKeluarPerBulan->first(function ($item) use ($bulan, $tahun) {
                return intval($item->bulan) === $bulan && intval($item->tahun) === $tahun;
            });
            $keluarData[] = $foundKeluar ? $foundKeluar->total : 0;
        }

        // Ambil periode 1 bulan terakhir
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Hitung total penjualan per barang bulan ini
        $barangPenjualan = DB::table('barang_keluar_details')
            ->join('barang_keluars', 'barang_keluars.id', '=', 'barang_keluar_details.barang_keluar_id')
            ->join('barang_masuks', 'barang_masuks.id', '=', 'barang_keluar_details.barang_masuk_id')
            ->select(
                'barang_masuks.nama_barang',
                DB::raw('SUM(barang_keluar_details.jumlah_keluar) as total_terjual')
            )
            ->whereBetween('barang_keluars.tanggal_keluar', [$startOfMonth, $endOfMonth])
            ->groupBy('barang_masuks.nama_barang')
            ->get();

        // Bagi data menjadi dua kategori
        $barangCepatHabis = [];
        $barangJarangLaku = [];

        foreach ($barangPenjualan as $row) {
            if ($row->total_terjual > 100) {
                $barangCepatHabis[] = [
                    'nama' => $row->nama_barang,
                    'jumlah' => $row->total_terjual
                ];
            } elseif ($row->total_terjual <= 10) {
                $barangJarangLaku[] = [
                    'nama' => $row->nama_barang,
                    'jumlah' => $row->total_terjual
                ];
            }
        }

        // Overstock query
        $barangOverstock = Barang::whereColumn('stok', '>', 'stok_maksimum')
            ->select('nama_barang', 'stok')
            ->get();

        // Return
        return view('layouts-page.dashboard', compact(
            'totalBarang',
            'stokHabis',
            'stokMinimum',
            'barangKadaluwarsa',
            'recentBarang',
            'labels',
            'masukData',
            'keluarData',
            'barangCepatHabis',
            'barangJarangLaku',
            'barangOverstock'
        ));
    }
}
