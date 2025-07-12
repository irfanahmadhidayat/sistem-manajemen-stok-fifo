@extends('layouts-page.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Stok Barang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Laporan</li>
                            <li class="breadcrumb-item active">Stok Barang</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mx-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3 float-right">
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#modal-default" id="print-stok">
                                        <i class="fas fa-print"></i> &nbsp;Print PDF
                                    </button>
                                </div>
                                <div class="form-group mt-4">
                                    <label for="opsi-laporan-stok">Filter Stok Berdasarkan :</label>
                                    <select class="form-control" name="opsi-laporan-stok" id="opsi-laporan-stok">
                                        <option value="semua" selected>Semua</option>
                                        <option value="stok-habis">Stok Habis</option>
                                    </select>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_id" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#table_id').DataTable({
                paging: true
            });

            loadData('semua');

            $('#opsi-laporan-stok').on('change', function() {
                var selectedOption = $(this).val();
                loadData(selectedOption);
            });

            function loadData(selectedOption) {
                $.ajax({
                    url: '/laporan-stok/get-data',
                    type: 'GET',
                    data: {
                        opsi: selectedOption
                    },
                    success: function(response) {
                        table.clear().draw();

                        let counter = 1;
                        $.each(response, function(index, item) {
                            var row = [
                                counter++,
                                item.kode_barang,
                                item.nama_barang,
                                item.stok
                            ];
                            table.row.add(row);
                        });
                        table.draw();
                    }
                });

            }

            $('#print-stok').on('click', function() {
                var selectedOption = $('#opsi-laporan-stok').val();
                var url = '/laporan-stok/print-stok?opsi=' + selectedOption;
                window.open(url, '_blank');
            });
        });
    </script>
@endsection
