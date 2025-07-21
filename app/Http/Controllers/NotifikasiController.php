<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function fetch()
    {
        $today = Carbon::today();

        // Stok habis
        $stokHabis = Barang::where('stok', 0)->get();

        // Stok di bawah minimum
        $stokMinimum = Barang::whereColumn('stok', '<', 'stok_minimum')
            ->where('stok', '>', 0)
            ->get();

        // Overstock
        $stokOver = Barang::whereColumn('stok', '>', 'stok_maksimum')->get();

        // Barang expired
        $expired = BarangMasuk::whereDate('tanggal_kadaluwarsa', '<=', $today)->get();

        // Barang mendekati kadaluwarsa
        $nearExpired = BarangMasuk::whereDate('tanggal_kadaluwarsa', '>', $today)
            ->whereDate('tanggal_kadaluwarsa', '<=', $today->copy()->addMonth())
            ->get();

        // Format notifikasi
        $notifications = [];

        foreach ($stokHabis as $item) {
            $notifications[] = [
                'icon' => 'fas fa-box',
                'text' => "Stok habis: {$item->nama_barang}",
                'time' => 'Hari ini'
            ];
        }

        foreach ($stokMinimum as $item) {
            $notifications[] = [
                'icon' => 'fas fa-box-open',
                'text' => "Stok di bawah minimum: {$item->nama_barang}",
                'time' => 'Hari ini'
            ];
        }

        foreach ($stokOver as $item) {
            $notifications[] = [
                'icon' => 'fas fa-warehouse',
                'text' => "Overstock: {$item->nama_barang}",
                'time' => 'Hari ini'
            ];
        }

        foreach ($expired as $item) {
            $notifications[] = [
                'icon' => 'fas fa-exclamation-triangle',
                'text' => "Expired: {$item->nama_barang}",
                'time' => "Expired"
            ];
        }

        foreach ($nearExpired as $item) {
            $diffDays = $today->diffInDays(Carbon::parse($item->tanggal_kadaluwarsa));
            $notifications[] = [
                'icon' => 'fas fa-hourglass-half',
                'text' => "Akan kadaluwarsa dalam {$diffDays} hari: {$item->nama_barang}",
                'time' => "{$diffDays} hari lagi"
            ];
        }

        return response()->json([
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }
}
