@extends('layouts-page.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Stok Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Barang</a></li>
                            <li class="breadcrumb-item active">Stok Penjualan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main Content -->
        <div class="content mx-3">
            <div class="container-fluid">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($namaBarangDipilih)
                                    <div class="mb-3 text-right">
                                        <a href="{{ route('stok.penjualan.print', ['nama_barang' => $namaBarangDipilih]) }}"
                                            class="btn btn-danger" target="_blank">
                                            <i class="fas fa-print"></i> &nbsp;Print PDF
                                        </a>
                                    </div>
                                @endif
                                <form method="GET" action="{{ route('stok.penjualan') }}">
                                    <div class="form-group">
                                        <label for="nama_barang">Pilih Nama Barang:</label>
                                        <select name="nama_barang" id="nama_barang" class="form-control"
                                            onchange="this.form.submit()">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach ($namaBarangs as $nama)
                                                <option value="{{ $nama }}"
                                                    {{ $nama == $namaBarangDipilih ? 'selected' : '' }}>
                                                    {{ $nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($namaBarangDipilih)
                    <div class="row">
                        <!-- Batch Masuk -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Batch Masuk - {{ $namaBarangDipilih }}</h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped">
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
                                                    <td colspan="6" class="text-center">Tidak ada data batch masuk.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if (count($batchMasuk))
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-right">TOTAL</th>
                                                    <th>{{ $totalMasuk }}</th>
                                                    <th>{{ $totalSisa }}</th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Batch Keluar -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Batch Keluar - {{ $namaBarangDipilih }}</h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Transaksi Keluar</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Jumlah Keluar</th>
                                                <th>Dari Batch Masuk (Kode)</th>
                                                <th>Tgl Masuk</th>
                                                <th>Tgl Kadaluwarsa</th>
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
                                                    <td colspan="7" class="text-center">Tidak ada data batch keluar.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if (count($batchKeluar))
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">TOTAL</th>
                                                    <th>{{ $totalKeluar }}</th>
                                                    <th colspan="3"></th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('#nama_barang').select2({
                placeholder: "-- Pilih Barang --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
