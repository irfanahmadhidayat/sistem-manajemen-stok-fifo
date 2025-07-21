@extends('layouts-page.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content mx-3">
            <div class="container-fluid">
                <!-- row: metric cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalBarang }}</h3>
                                <p>Total Barang</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $stokHabis }}</h3>
                                <p>Stok Habis</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $stokMinimum }}</h3>
                                <p>Stok di Bawah Minimum</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $barangKadaluwarsa }}</h3>
                                <p>Hampir Kadaluwarsa</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <!-- row: chart -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Grafik Barang Masuk & Keluar 3 Bulan Terakhir</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="barangChart" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Grafik Barang Cepat Habis, Jarang Laku & Overstock 1 Bulan Terakhir
                                </h3>
                            </div>
                            <div class="card-body">
                                <canvas id="barangStatistikChart" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('barangChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'Barang Masuk',
                        backgroundColor: '#28a745',
                        data: @json($masukData)
                    },
                    {
                        label: 'Barang Keluar',
                        backgroundColor: '#dc3545',
                        data: @json($keluarData)
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const cepatHabisLabels = @json(collect($barangCepatHabis)->pluck('nama'));
        const cepatHabisData = @json(collect($barangCepatHabis)->pluck('jumlah'));
        const jarangLakuLabels = @json(collect($barangJarangLaku)->pluck('nama'));
        const jarangLakuData = @json(collect($barangJarangLaku)->pluck('jumlah'));
        const overstockLabels = @json(collect($barangOverstock)->pluck('nama_barang'));
        const overstockData = @json(collect($barangOverstock)->pluck('stok'));

        // Gabungkan semua label unik
        const semuaLabels = [...new Set([
            ...cepatHabisLabels,
            ...jarangLakuLabels,
            ...overstockLabels
        ])];

        // Data untuk masing-masing dataset
        const dataCepatHabis = semuaLabels.map(label => {
            const idx = cepatHabisLabels.indexOf(label);
            return idx !== -1 ? cepatHabisData[idx] : 0;
        });
        const dataJarangLaku = semuaLabels.map(label => {
            const idx = jarangLakuLabels.indexOf(label);
            return idx !== -1 ? jarangLakuData[idx] : 0;
        });
        const dataOverstock = semuaLabels.map(label => {
            const idx = overstockLabels.indexOf(label);
            return idx !== -1 ? overstockData[idx] : 0;
        });

        // Buat chart
        const ctxLaku = document.getElementById('barangStatistikChart').getContext('2d');
        new Chart(ctxLaku, {
            type: 'bar',
            data: {
                labels: semuaLabels,
                datasets: [{
                        label: 'Cepat Habis',
                        backgroundColor: 'rgba(40,167,69,0.8)',
                        borderColor: 'rgba(40,167,69,1)',
                        data: dataCepatHabis
                    },
                    {
                        label: 'Jarang Laku',
                        backgroundColor: 'rgba(220,53,69,0.8)',
                        borderColor: 'rgba(220,53,69,1)',
                        data: dataJarangLaku
                    },
                    {
                        label: 'Overstock',
                        backgroundColor: 'rgba(255,193,7,0.8)',
                        borderColor: 'rgba(255,193,7,1)',
                        data: dataOverstock
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
@endsection
