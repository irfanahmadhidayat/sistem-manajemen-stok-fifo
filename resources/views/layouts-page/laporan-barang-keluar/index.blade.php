@extends('layouts-page.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Barang Keluar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                            <li class="breadcrumb-item active">Barang Keluar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mx-3">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mt-2 text-right">
                                    <a href="{{ route('laporan.barang-keluar.print', ['nama_barang' => $namaBarangDipilih]) }}"
                                        class="btn btn-danger" target="_blank">
                                        <i class="fas fa-print"></i> &nbsp;Print PDF
                                    </a>
                                </div>
                                <form method="GET" action="{{ route('laporan.barang-keluar') }}">
                                    <div class="form-group">
                                        <label for="nama_barang">Pilih Nama Barang:</label>
                                        <select name="nama_barang" id="nama_barang" class="form-control"
                                            onchange="this.form.submit()">
                                            <option value="">-- Semua Barang --</option>
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

                @if ($batchKeluar->count())
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">
                                        Barang Keluar
                                        {{ $namaBarangDipilih ? '- ' . $namaBarangDipilih : '(Semua Barang)' }}
                                    </h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped">
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
                                                <th colspan="3" class="text-right">TOTAL</th>
                                                <th>{{ $totalKeluar }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">Tidak ada data barang keluar.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('#nama_barang').select2({
                placeholder: "-- Semua Barang --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
