<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
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
    <h2>Laporan Barang Keluar {{ $namaBarangDipilih ? '- ' . $namaBarangDipilih : '(Semua Barang)' }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Keluar</th>
                <th>Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batchKeluar as $index => $keluar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $keluar->kode_transaksi }}</td>
                    <td>{{ $keluar->tanggal_keluar }}</td>
                    <td>{{ $keluar->jumlah_keluar }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">TOTAL</th>
                <th>{{ $totalKeluar }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
