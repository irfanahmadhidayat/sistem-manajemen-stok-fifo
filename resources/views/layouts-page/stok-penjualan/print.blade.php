<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Stok Penjualan - {{ $namaBarangDipilih }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Stok Penjualan Barang: {{ $namaBarangDipilih }}</h2>

    <h4>Batch Masuk</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Kadaluwarsa</th>
                <th>Jumlah Masuk</th>
                <th>Sisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batchMasuk as $index => $masuk)
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
            @if (count($batchMasuk))
                <tr>
                    <th colspan="4" style="text-align:right;">TOTAL</th>
                    <th>{{ $totalMasuk }}</th>
                    <th>{{ $totalSisa }}</th>
                </tr>
            @endif
        </tbody>
    </table>

    <h4>Batch Keluar</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi Keluar</th>
                <th>Tanggal Keluar</th>
                <th>Jumlah Keluar</th>
                <th>Dari Batch Masuk</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Kadaluwarsa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batchKeluar as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->barangKeluar->kode_transaksi }}</td>
                    <td>{{ $detail->barangKeluar->tanggal_keluar }}</td>
                    <td>{{ $detail->jumlah_keluar }}</td>
                    <td>{{ $detail->barangMasuk->kode_transaksi }}</td>
                    <td>{{ $detail->barangMasuk->tanggal_masuk }}</td>
                    <td>{{ $detail->barangMasuk->tanggal_kadaluwarsa }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data batch keluar.</td>
                </tr>
            @endforelse
            @if (count($batchKeluar))
                <tr>
                    <th colspan="3" style="text-align:right;">TOTAL</th>
                    <th>{{ $totalKeluar }}</th>
                    <th colspan="3"></th>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak Tanggal: {{ date('d-m-Y') }}
    </div>
</body>

</html>
