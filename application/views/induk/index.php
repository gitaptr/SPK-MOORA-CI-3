<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fish"></i> Data Induk Ikan</h1>
</div>

<?= $this->session->flashdata('message'); ?>



<!-- /.card-header -->

<div class="card shadow mb-4">
    <div class="card-header py-2">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Induk Ikan</h6>
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
                        <th>Jenis Kelamin</th>
                        <th>Jumlah</th>
                        <th>Kolam</th>
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
                            <td><?php echo $value->jenis_kelamin ?></td>
                            <td><?php echo $value->jumlah ?></td>
                            <td><?php echo $value->kode_kolam; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-warning btn-sm" onclick="showEditModal(<?= $value->id_induk ?>)" title="Edit Data"><i class="fa fa-edit"></i></a>
                                    <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?= base_url('Induk/destroy/' . $value->id_induk) ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                <h4 class="modal-title">Tambah Induk Ikan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('Induk/store') ?>">

                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option value="Betina">Betina</option>
                            <option value="Jantan">Jantan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Jumlah</label>
                        <input autocomplete="off" type="number" name="jumlah" min="1"required class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kolam</label>
                        <select name="kolam" class="form-control" required>
                            <option value="">--Pilih Kolam--</option>
                            <?php if (!empty($kolam_list)): ?>
                                <?php foreach ($kolam_list as $kolam): ?>
                                    <option value="<?= $kolam->id_kolam ?>"><?= $kolam->kode_kolam ?></option> <!-- Pastikan menggunakan id_kolam -->
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Kolam tidak tersedia</option>
                            <?php endif; ?>
                        </select>
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

<div class="modal fade" id="modal-Edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEdit" method="post" action="<?= base_url('Induk/update') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Induk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_induk" id="edit_id_induk" />
                    <div class="form-group">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin" id="edit_jenis_kelamin" required>
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option value="Betina">Betina</option>
                            <option value="Jantan">Jantan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" min="1" id="edit_jumlah" required />
                    </div>
                    <div class="form-group">
                        <label for="edit_kolam">Kolam</label>
                        <select name="kolam" class="form-control" id="edit_kolam" required>
                            <option value="<?= $kolam->id_kolam ?>"
                                <?= (isset($induk) && $induk->id_kolam == $kolam->id_kolam) ? 'selected' : '' ?>>
                                <?= $kolam->kode_kolam ?>
                            </option>

                            <?php if (!empty($kolam_list)): ?>
                                <?php foreach ($kolam_list as $kolam): ?>
                                    <option value="<?= $kolam->id_kolam ?>"
                                        <?= (isset($induk) && $induk->id_kolam == $kolam->id_kolam) ? 'selected' : '' ?>>
                                        <?= $kolam->kode_kolam ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Kolam tidak tersedia</option>
                            <?php endif; ?>
                        </select>
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
        // Menggunakan AJAX untuk mendapatkan data berdasarkan ID
        $.ajax({
            url: "<?= base_url('Induk/get_induk_by_id/') ?>" + id,
            method: "GET",
            dataType: "json",
            success: function(data) {
                // Isi data ke dalam input form di modal
                $('#edit_id_induk').val(data.id_induk);
                $('#edit_jenis_kelamin').val(data.jenis_kelamin); // Untuk select
                $('#edit_jumlah').val(data.jumlah);
                $('#edit_kolam').val(data.id_kolam); // Atur kolam

                // Tampilkan modal
                $('#modal-Edit').modal('show');
            },
            error: function() {
                alert('Gagal memuat data! Silakan coba lagi.');
            }
        });
    }
</script>

</body>

</html>