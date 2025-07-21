<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Laporan Barang Masuk {{ $namaBarangDipilih ? '- ' . $namaBarangDipilih : '(Semua Barang)' }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Kadaluwarsa</th>
                <th>Jumlah Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batchMasuk as $index => $masuk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $masuk->kode_transaksi }}</td>
                    <td>{{ $masuk->tanggal_masuk }}</td>
                    <td>{{ $masuk->tanggal_kadaluwarsa }}</td>
                    <td>{{ $masuk->jumlah_masuk }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align:right;">TOTAL</th>
                <th>{{ $totalMasuk }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
