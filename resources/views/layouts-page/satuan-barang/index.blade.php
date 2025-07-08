@extends('layouts-page.app')

@include('layouts-page.satuan-barang.create')
@include('layouts-page.satuan-barang.edit')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Satuan Barang</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Barang</a></li>
                            <li class="breadcrumb-item active">Satuan Barang</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content mx-3">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-default" id="button_tambah_satuan_barang">
                                        <i class="fas fa-plus"></i> Satuan Barang
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_id" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Satuan Barang</th>
                                                <th>Opsi</th>
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
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection

@section('script')
    <!-- Menampilkan Data Satuan Barang -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });
            $.ajax({
                url: "/satuan-barang/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let satuanBarang = `
                    <tr class="barang-row" id="index_${value.id}">
                        <td>${counter++}</td>   
                        <td>${value.satuan}</td>
                        <td>
                            <a href="javascript:void(0)" id="button_edit_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-md mb-2"><i class="far fa-edit"></i> </a>
                            <a href="javascript:void(0)" id="button_hapus_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-md mb-2"><i class="fas fa-trash"></i> </a>
                        </td>
                    </tr>
                `;
                        $('#table_id').DataTable().row.add($(satuanBarang)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Menambah Data Satuan Barang -->
    <script>
        // Menampilkan Modal
        $('body').on('click', '#button_tambah_satuan_barang', function() {
            $('#modal_tambah_satuan_barang').modal('show');
        });

        // Store Data
        $('#store').click(function(e) {
            e.preventDefault();

            let satuan_barang = $('#satuan_barang').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('satuan', satuan_barang);
            formData.append('_token', token);

            $.ajax({
                url: '/satuan-barang',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: '/satuan-barang/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let satuanBarang = `
                            <tr class="barang-row" id="index_${value.id}">
                                <td>${counter++}</td>   
                                <td>${value.satuan}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_edit_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                        `;
                                $('#table_id').DataTable().row.add($(satuanBarang))
                                    .draw(false);
                            });

                            $('#satuan_barang').val('');
                            $('#modal_tambah_satuan_barang').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.satuan && error.responseJSON
                        .satuan[0]) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.responseJSON.satuan[0],
                            showConfirmButton: true,
                        });
                    }
                }
            });
        });
    </script>

    <!-- Mengedit Data Satuan Barang -->
    <script>
        $('body').on('click', '#button_edit_satuan_barang', function() {
            let satuan_id = $(this).data('id');

            $.ajax({
                url: `/satuan-barang/${satuan_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#satuan_id').val(response.data.id);
                    $('#edit_satuan_barang').val(response.data.satuan);

                    $('#modal_edit_satuan_barang').modal('show');
                }
            });
        });

        $('#update').click(function(e) {
            e.preventDefault();

            let satuan_id = $('#satuan_id').val();
            let satuan_barang = $('#edit_satuan_barang').val();
            let token = $("meta[name='csrf-token']").attr('content');

            let formData = new FormData();
            formData.append('satuan', satuan_barang);
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/satuan-barang/${satuan_id}`,
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    let row = $(`#index_${response.data.id}`);
                    let rowData = row.find('td');
                    rowData.eq(1).text(response.data.satuan);

                    $('#modal_edit_satuan_barang').modal('hide');
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.satuan && error.responseJSON
                        .satuan[0]) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.responseJSON.satuan[0],
                            showConfirmButton: true,
                        });
                    }
                }
            });
        });
    </script>

    <!-- Menghapus Data Satuan Barang -->
    <script>
        $('body').on('click', '#button_hapus_satuan_barang', function() {
            let satuan_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/satuan-barang/${satuan_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $('#table_id').DataTable().clear().draw();

                            $.ajax({
                                url: "/satuan-barang/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let satuanBarang = `
                                    <tr class="barang-row" id="index_${value.id}">
                                        <td>${counter++}</td>   
                                        <td>${value.satuan}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="button_edit_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                            <a href="javascript:void(0)" id="button_hapus_satuan_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                `;
                                        $('#table_id').DataTable().row.add(
                                            $(satuanBarang)).draw(false);
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
