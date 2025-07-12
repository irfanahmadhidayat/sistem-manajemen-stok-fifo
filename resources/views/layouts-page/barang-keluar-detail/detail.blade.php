@extends('layouts-page.app')

@section('content')
    <div class="container">
        <h2>Detail Barang Keluar</h2>
        <div class="card mb-4">
            <div class="card-body">
                <strong>Kode Transaksi:</strong> {{ $barangKeluar->kode_transaksi }}<br>
                <strong>Nama Barang:</strong> {{ $barangKeluar->nama_barang }}<br>
                <strong>Tanggal Keluar:</strong> {{ $barangKeluar->tanggal_keluar }}<br>
                <strong>Jumlah Keluar:</strong> {{ $barangKeluar->jumlah_keluar }}
            </div>
        </div>

        <h4>Batch Barang Masuk yang Digunakan</h4>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Transaksi Masuk</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Kadaluwarsa</th>
                    <th>Jumlah Keluar dari Batch Ini</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangKeluar->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->barangMasuk->kode_transaksi }}</td>
                        <td>{{ $detail->barangMasuk->tanggal_masuk }}</td>
                        <td>{{ $detail->barangMasuk->tanggal_kadaluwarsa }}</td>
                        <td>{{ $detail->jumlah_keluar }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada detail batch keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4>Semua Batch Barang Masuk (Sisa Stok)</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Transaksi Masuk</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Kadaluwarsa</th>
                    <th>Jumlah Masuk</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangMasuk as $index => $masuk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $masuk->kode_transaksi }}</td>
                        <td>{{ $masuk->tanggal_masuk }}</td>
                        <td>{{ $masuk->tanggal_kadaluwarsa }}</td>
                        <td>{{ $masuk->jumlah_masuk }}</td>
                        <td>{{ $masuk->sisa }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data batch masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
