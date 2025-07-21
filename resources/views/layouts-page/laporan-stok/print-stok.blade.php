<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2,
        p {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #eee;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>Laporan Stok Barang</h2>
    <p>Keterangan: {{ $selectedOption }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->stok }} {{ $barang->satuan->satuan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada data stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak Tanggal: {{ date('d-m-Y') }}
    </div>
</body>

</html>
