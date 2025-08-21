<div class="modal fade" id="modal_tambah_satuan_barang">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Satuan Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Satuan Barang</label>
                        <input type="text" class="form-control" name="satuan" id="satuan_barang">
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-satuan_barang"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="store">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
