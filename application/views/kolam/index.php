<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-water"></i> Data Kolam</h1>


</div>

<?= $this->session->flashdata('message'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-2">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Kolam Ikan</h6>
            <h6 class="btn btn-success" data-toggle="modal" data-target="#modal-Input">
                <i class="fa fa-plus"></i> Tambah Data
            </h6>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Kode Kolam</th>
                        <th>Luas Kolam (m2)</th>
                        <th>Kapasitas</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($list as $data) {
                    ?>
                        <tr align="center">
                            <td><?= $no ?></td>
                            <td align="left"><?= $data->kode_kolam ?></td>
                            <td align="left"><?= $data->luas_kolam ?></td>
                            <td align="left"><?= $data->kapasitas ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-warning btn-sm" onclick="showEditModal(<?= $data->id_kolam ?>)" title="Edit Data">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('Kolam/destroy/' . $data->id_kolam) ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-Input">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kolam</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('Kolam/store') ?>">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Kolam</label>
                        <input autocomplete="off" type="text" name="kode_kolam" required class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Luas Kolam (m2)</label>
                        <input autocomplete="off" type="number" name="luas_kolam" required class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kapasitas</label>
                        <input autocomplete="off" type="number" name="kapasitas" required class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-sync-alt"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="modal-Edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEdit" method="post" action="<?= base_url('Kolam/update') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kolam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_kolam" id="edit_id_kolam" />
                    <div class="form-group">
                        <label for="edit_nama_ikan">Kode Kolam </label>
                        <input type="text" class="form-control" name="kode_kolam" id="edit_kode_kolam" required />
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah">Luas Kolam (m2)</label>
                        <input type="number" class="form-control" name="luas_kolam" id="edit_luas_kolam" required />
                    </div>
                    <div class="form-group">
                        <label for="edit_kolam">Kapasitas</label>
                        <input type="text" class="form-control" name="kapasitas" id="edit_kapasitas" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                    <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
<script>
    function showEditModal(id) {
        $.ajax({
            url: '<?= base_url("Kolam/show") ?>', // Endpoint untuk mengambil data
            type: 'GET',
            data: {
                id_kolam: id
            }, // Kirim ID kolam sebagai parameter
            dataType: 'json',
            success: function(data) {
                // Isi data ke input form di modal
                $('#edit_id_kolam').val(data.id_kolam);
                $('#edit_kode_kolam').val(data.kode_kolam);
                $('#edit_luas_kolam').val(data.luas_kolam);
                $('#edit_kapasitas').val(data.kapasitas);

                // Tampilkan modal edit
                $('#modal-Edit').modal('show');
            },
            error: function() {
                alert('Gagal mengambil data. Silakan coba lagi.');
            }
        });
    }
</script>

</body>

</html>