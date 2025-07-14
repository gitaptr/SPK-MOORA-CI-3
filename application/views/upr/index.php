<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data UPR</h1>


</div>

<?= $this->session->flashdata('message'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data UPR </h6>
            <h6 class="btn btn-info" data-toggle="modal" data-target="#modal-Input">
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
                        <th>Nama</th>
                        <th>Wilayah</th>
                        <th>Penyuluh</th>
                        <th>No HP</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($list as $data => $value) {
                    ?>
                        <tr align="center">
                            <td><?= $no ?></td>
                            <td align="left"><?php echo $value->nama_upr ?></td>
                            <td align="left"><?php echo $value->wilayah ?></td>
                            <td align="left"><?php echo $value->penyuluh ?></td>
                            <td align="left"><?php echo $value->no_hp ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-warning btn-sm" onclick="showEditModal(<?= $value->id_upr ?>)" title="Edit Data"><i class="fa fa-edit"></i></a>

                                    <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?= base_url('Upr/destroy/' . $value->id_upr) ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                <h4 class="modal-title">Tambah UPR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('Upr/store') ?>">
                    <div class="form-group ">
                        <label class="font-weight-bold">Nama</label>
                        <input autocomplete="off" type="text" name="nama_upr" required class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Wilayah</label>
                        <select name="id_wilayah" class="form-control" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w) { ?>
                                <option value="<?= $w->id_wilayah ?>"><?= $w->nama_wilayah ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Penyuluh</label>
                        <select name="id_user" class="form-control" required>
                            <option value="">Pilih Penyuluh</option>
                            <?php foreach ($penyuluh as $p) { ?>
                                <option value="<?= $p->id_user ?>"><?= $p->nama ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">No HP</label>
                        <input autocomplete="off"
                            type="text"
                            name="no_hp"
                            id="no_hp"
                            required
                            maxlength="12"
                            pattern="08[0-9]{8,10}"
                            class="form-control"
                            title="Nomor HP harus diawali dengan 08 dan terdiri dari 10–12 digit angka" />
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
            <form id="formEdit" method="post" action="<?= base_url('Upr/update') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data UPR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_upr" id="edit_id_upr" />
                    <div class="form-group">
                        <label class="font-weight-bold">Nama UPR</label>
                        <input type="text" class="form-control" name="nama_upr" id="edit_nama_upr" required />
                    </div>
                    <div class="form-group">
                        <label>Wilayah</label>
                        <select name="id_wilayah" id="edit_id_wilayah" class="form-control" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w) { ?>
                                <option value="<?= $w->id_wilayah ?>"><?= $w->nama_wilayah ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Penyuluh</label>
                        <select name="id_user" id="edit_id_user" class="form-control" required>
                            <option value="">Pilih Penyuluh</option>
                            <?php foreach ($penyuluh as $p) { ?>
                                <option value="<?= $p->id_user ?>"><?= $p->nama ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">No HP</label>
                        <input type="text" class="form-control" name="no_hp" id="edit_no_hp" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
<script>
    ['no_hp', 'edit_no_hp'].forEach(function(id) {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
            });
        }
    });
</script>



<script>
    function showEditModal(id) {
        $.ajax({
            url: "<?= base_url('Upr/get_upr_by_id/') ?>" + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#edit_id_upr').val(data.id_upr);
                $('#edit_nama_upr').val(data.nama_upr);
                $('#edit_id_wilayah').val(data.id_wilayah);
                $('#edit_id_user').val(data.id_user);
                $('#edit_no_hp').val(data.no_hp);
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