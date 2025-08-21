<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = [];

        // 1. Barang habis stok
        $stokHabis = Barang::where('stok', 0)->get();
        foreach ($stokHabis as $barang) {
            $notifications[] = [
                'type' => 'stock_empty',
                'title' => 'Stok Habis',
                'message' => "Barang <b>{$barang->nama_barang}</b> dengan kode <b>{$barang->kode_barang}</b> telah habis",
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'priority' => 1
            ];
        }

        // 2. Barang di bawah stok minimum
        $stokMinimum = Barang::whereColumn('stok', '<', 'stok_minimum')
            ->where('stok', '>', 0)
            ->get();
        foreach ($stokMinimum as $barang) {
            $notifications[] = [
                'type' => 'stock_low',
                'title' => 'Stok Rendah',
                'message' => "Barang <b>{$barang->nama_barang}</b> di bawah stok minimum ({$barang->stok}/{$barang->stok_minimum})",
                'icon' => 'fas fa-exclamation-circle',
                'color' => 'warning',
                'priority' => 2
            ];
        }

        // 3. Barang overstock
        $overstock = Barang::whereColumn('stok', '>', 'stok_maksimum')->get();
        foreach ($overstock as $barang) {
            $notifications[] = [
                'type' => 'stock_over',
                'title' => 'Overstock',
                'message' => "Barang <b>{$barang->nama_barang}</b> melebihi stok maksimum ({$barang->stok}/{$barang->stok_maksimum})",
                'icon' => 'fas fa-arrow-up',
                'color' => 'info',
                'priority' => 3
            ];
        }

        // 4. Barang mendekati kadaluwarsa
        $now = Carbon::now();

        // Kadaluwarsa hari ini
        $expiryToday = BarangMasuk::where('sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ])
            ->get();
        foreach ($expiryToday as $item) {
            $notifications[] = [
                'type' => 'expiry_today',
                'title' => 'Kadaluwarsa Hari Ini',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> akan kadaluwarsa hari ini",
                'icon' => 'fas fa-clock',
                'color' => 'danger',
                'priority' => 1
            ];
        }

        // Kadaluwarsa besok (1 hari)
        $expiry1Day = BarangMasuk::where('sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [
                $now->copy()->addDay()->startOfDay(),
                $now->copy()->addDay()->endOfDay()
            ])
            ->get();
        foreach ($expiry1Day as $item) {
            $notifications[] = [
                'type' => 'expiry_1day',
                'title' => 'Kadaluwarsa Besok',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> akan kadaluwarsa besok",
                'icon' => 'fas fa-clock',
                'color' => 'danger',
                'priority' => 1
            ];
        }

        // 3 hari
        $expiry3Days = BarangMasuk::where('sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [
                $now->copy()->addDays(2)->startOfDay(),
                $now->copy()->addDays(3)->endOfDay()
            ])
            ->get();
        foreach ($expiry3Days as $item) {
            $notifications[] = [
                'type' => 'expiry_3days',
                'title' => 'Kadaluwarsa 3 Hari',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> akan kadaluwarsa dalam 3 hari",
                'icon' => 'fas fa-clock',
                'color' => 'warning',
                'priority' => 2
            ];
        }

        // 2 minggu
        $expiry2Weeks = BarangMasuk::where('sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [
                $now->copy()->addDays(13)->startOfDay(),
                $now->copy()->addDays(14)->endOfDay()
            ])
            ->get();
        foreach ($expiry2Weeks as $item) {
            $notifications[] = [
                'type' => 'expiry_2weeks',
                'title' => 'Kadaluwarsa 2 Minggu',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> akan kadaluwarsa dalam 2 minggu",
                'icon' => 'fas fa-calendar-alt',
                'color' => 'warning',
                'priority' => 3
            ];
        }

        // 1 bulan
        $expiry1Month = BarangMasuk::where('sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [
                $now->copy()->addDays(29)->startOfDay(),
                $now->copy()->addMonth()->endOfDay()
            ])
            ->get();
        foreach ($expiry1Month as $item) {
            $notifications[] = [
                'type' => 'expiry_1month',
                'title' => 'Kadaluwarsa 1 Bulan',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> akan kadaluwarsa dalam 1 bulan",
                'icon' => 'fas fa-calendar',
                'color' => 'info',
                'priority' => 4
            ];
        }

        // 5. Barang sudah kadaluwarsa
        $expired = BarangMasuk::where('sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<', $now->startOfDay())
            ->get();
        foreach ($expired as $item) {
            $expiredDays = Carbon::parse($item->tanggal_kadaluwarsa)->diffInDays($now);
            $notifications[] = [
                'type' => 'expired',
                'title' => 'Sudah Kadaluwarsa',
                'message' => "Barang <b>{$item->nama_barang}</b> dengan kode transaksi <b>{$item->kode_transaksi}</b> sudah kadaluwarsa {$expiredDays} hari yang lalu",
                'icon' => 'fas fa-times-circle',
                'color' => 'danger',
                'priority' => 1
            ];
        }

        // Sort berdasarkan prioritas
        usort($notifications, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return response()->json([
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }
}
