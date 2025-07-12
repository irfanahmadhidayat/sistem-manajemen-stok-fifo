@extends('layouts-page.app')

@include('layouts-page.barang-keluar.create')

@section('content')
    <div class="content-wrapper">
        <div class="content-header mx-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Barang Keluar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Transaksi</li>
                            <li class="breadcrumb-item active">Barang Keluar</li>
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
                                <div class="mb-3">
                                    <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangKeluar"><i
                                            class="fa fa-plus"></i>
                                        Barang Keluar</a>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_id" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Transaksi</th>
                                                <th>Nama Barang</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Jumlah Keluar</th>
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
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Select2 Autocomplete -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.js-example-basic-single').select2();

                $('#nama_barang').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var nama_barang = selectedOption.text();

                    $.ajax({
                        url: '/api/barang-keluar',
                        type: 'GET',
                        data: {
                            nama_barang: nama_barang,
                        },
                        success: function(response) {
                            if (response && (response.stok || response.stok === 0) &&
                                response.satuan_id) {
                                $('#stok').val(response.stok);
                                getSatuanName(response.satuan_id, function(satuan) {
                                    $('#satuan_id').val(satuan);
                                });
                            } else if (response && response.stok === 0) {
                                $('#stok').val(0);
                                $('#satuan_id').val('');
                            }
                        },
                    });

                    function getSatuanName(satuanId, callback) {
                        $.getJSON('{{ url('api/satuan') }}', function(satuans) {
                            var satuan = satuans.find(function(s) {
                                return s.id === satuanId;
                            });
                            callback(satuan ? satuan.satuan : '');
                        });
                    }
                });
            }, 500);
        });
    </script>

    <!-- Generate Kode Transaksi Otomatis -->
    <script>
        function generateKodeTransaksi() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var tanggalFormatted = dd + mm + yyyy;

            var nomorRandom = Math.floor(10000 + Math.random() * 90000);

            var kodeTransaksi = 'KLR-' + tanggalFormatted + '-' + nomorRandom;

            return kodeTransaksi;
        }

        $(document).ready(function() {
            generateKodeTransaksi();
        });
    </script>

    <!-- Menampilkan Data Barang Keluar -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });

            $.ajax({
                url: "/barang-keluar/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let barangKeluar = `
                <tr class="barang-row" id="index_${value.id}">
                    <td>${counter++}</td>   
                    <td>${value.kode_transaksi}</td>
                    <td>${value.nama_barang}</td>
                    <td>${value.tanggal_keluar}</td>
                    <td>${value.jumlah_keluar}</td>
                    <td>
                        <a href="javascript:void(0)" id="button_hapus_barangKeluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-md mb-2"><i class="fas fa-trash"></i> </a>
                    </td>
                </tr>
            `;
                        $('#table_id').DataTable().row.add($(barangKeluar)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Menambah Data Barang Keluar -->
    <script>
        $('body').on('click', '#button_tambah_barangKeluar', function() {
            $('#modal_tambah_barangKeluar').modal('show');
            var kode = generateKodeTransaksi();
            $('#kode_transaksi').val(kode);
        });

        $('#store').click(function(e) {
            e.preventDefault();

            let kode_transaksi = $('#kode_transaksi').val();
            let nama_barang = $('#nama_barang').val();
            let tanggal_keluar = $('#tanggal_keluar').val();
            let jumlah_keluar = $('#jumlah_keluar').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('kode_transaksi', kode_transaksi);
            formData.append('nama_barang', nama_barang);
            formData.append('tanggal_keluar', tanggal_keluar);
            formData.append('jumlah_keluar', jumlah_keluar);
            formData.append('_token', token);

            $.ajax({
                url: '/barang-keluar',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: '/barang-keluar/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            $('#table-barangs').html('');

                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let barangKeluar = `
                                <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kode_transaksi}</td>
                                    <td>${value.nama_barang}</td>
                                    <td>${value.tanggal_keluar}</td>
                                    <td>${value.jumlah_keluar}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_hapus_barangKeluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                             `;
                                $('#table_id').DataTable().row.add($(barangKeluar))
                                    .draw(false);
                            });

                            $('#kode_transaksi').val('');
                            $('#nama_barang').val('');
                            $('#tanggal_keluar').val('');
                            $('#jumlah_keluar').val('');
                            $('#stok').val('');

                            $('#modal_tambah_barangKeluar').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.kode_transaksi && error.responseJSON
                        .kode_transaksi[0]) {
                        $('#alert-kode_transaksi').removeClass('d-none').addClass('d-block')
                            .html(error.responseJSON.kode_transaksi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.nama_barang && error.responseJSON
                        .nama_barang[0]) {
                        $('#alert-nama_barang').removeClass('d-none').addClass('d-block')
                            .html(error.responseJSON.nama_barang[0]);
                    }

                    if (error.responseJSON && error.responseJSON.tanggal_keluar && error.responseJSON
                        .tanggal_keluar[0]) {
                        $('#alert-tanggal_keluar').removeClass('d-none').addClass('d-block')
                            .html(error.responseJSON.tanggal_keluar[0]);
                    }

                    if (error.responseJSON && error.responseJSON.jumlah_keluar && error.responseJSON
                        .jumlah_keluar[0]) {
                        $('#alert-jumlah_keluar').removeClass('d-none').addClass('d-block')
                            .html(error.responseJSON.jumlah_keluar[0]);
                    }
                }
            });
        });
    </script>

    <!-- Hapus Data Barang Keluar -->
    <script>
        $('body').on('click', '#button_hapus_barangKeluar', function() {
            let kode_transaksi_keluar = $(this).data('kode');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus transaksi ini beserta semua batch keluar?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang-keluar/${kode_transaksi_keluar}/batch`,
                        type: "DELETE",
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            });

                            // Hilangkan baris tabel yang berkaitan
                            $(`tr[data-kode='${kode_transaksi_keluar}']`).remove();

                            // Atau reload tabel via AJAX jika diperlukan
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON.message ?? 'Terjadi kesalahan.'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
