<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-th-list"></i> Data Sub Kriteria</h1>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-info"></i> Pilih Jenis Kelamin</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" onchange="filterJenisKelamin()">
                        <option value="">Semua</option>
                        <option value="Jantan" <?= $selected_jenis_kelamin == "Jantan" ? "selected" : "" ?>>Jantan</option>
                        <option value="Betina" <?= $selected_jenis_kelamin == "Betina" ? "selected" : "" ?>>Betina</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function filterJenisKelamin() {
        var jenis_kelamin = document.getElementById("jenis_kelamin").value;
        window.location.href = "<?= base_url('sub_kriteria?jenis_kelamin=') ?>" + jenis_kelamin;
    }
</script>

<?php if ($kriteria == NULL): ?>
    <div class="card shadow mb-4">
        <!-- /.card-header -->
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success"></i> Daftar Data Sub Kriteria</h6>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                Data masih kosong.
            </div>
        </div>
    </div>
<?php endif ?>



<?php foreach ($kriteria as $key): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-info"></i> <?= $key->keterangan . " (" . $key->kode_kriteria . ")" ?></h6>
                <?php if (!$read_only && $selected_jenis_kelamin): ?> <!-- Hanya tampilkan jika bukan read-only dan jenis kelamin dipilih -->
                    <a href="#tambah<?= $key->id_kriteria ?>" data-toggle="modal" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
                <?php endif; ?>
            </div>
        </div>



        <div class="modal fade" id="tambah<?= $key->id_kriteria ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Tambah <?= $key->keterangan ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <?= form_open('Sub_kriteria/store', ['id' => 'formTambah' . $key->id_kriteria, 'class' => 'form-submit']) ?>
                    <div class="modal-body">
                        <input type="text" name="id_kriteria" value="<?= $key->id_kriteria ?>" hidden>
                        <div class="form-group">
                            <label for="deskripsi" class="font-weight-bold">Nama Sub Kriteria</label>
                            <input autocomplete="off" type="text" id="deskripsi" class="form-control" name="deskripsi" required>
                        </div>
                        <div class="form-group">
                            <label for="nilai" class="font-weight-bold">Nilai</label>
                            <input autocomplete="off" type="number" id="nilai" name="nilai" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Nama Sub Kriteria</th>
                            <th>Nilai</th>
                            <?php if (!$read_only): ?> <!-- Hanya tampilkan jika bukan read-only -->
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sub_kriteria1 = $this->Sub_Kriteria_model->data_sub_kriteria($key->id_kriteria, $selected_jenis_kelamin);
                        $no = 1;
                        foreach ($sub_kriteria1 as $key):
                        ?>
                            <tr align="center">
                                <td><?= $no ?></td>
                                <td align="left"><?= $key['deskripsi'] ?></td>
                                <td><?= $key['nilai'] ?></td>
                                <?php if (!$read_only): ?> <!-- Hanya tampilkan jika bukan read-only -->
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a data-toggle="modal" title="Edit Data" href="#editsk<?= $key['id_sub_kriteria'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?= base_url('Sub_kriteria/destroy/' . $key['id_sub_kriteria']) ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <div class="modal fade" id="editsk<?= $key['id_sub_kriteria'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edit <?= $key['deskripsi'] ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        </div>
                                        <?= form_open('Sub_kriteria/update/' . $key['id_sub_kriteria'], ['id' => 'formEdit' . $key['id_sub_kriteria'], 'class' => 'form-submit']) ?>
                                        <?= form_hidden('id_sub_kriteria', $key['id_sub_kriteria']) ?>
                                        <div class="modal-body">
                                            <input type="text" name="id_kriteria" value="<?= $key['id_kriteria'] ?>" hidden>
                                            <div class="form-group">
                                                <label for="deskripsi" class="font-weight-bold">Nama Sub Kriteria</label>
                                                <input type="text" id="deskripsi" autocomplete="off" class="form-control" value="<?= $key['deskripsi'] ?>" name="deskripsi" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilai" class="font-weight-bold">Nilai</label>
                                                <input type="number" autocomplete="off" id="nilai" name="nilai" class="form-control" value="<?= $key['nilai'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $no++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php $this->load->view('layouts/footer_admin'); ?>
<script>
    $(document).ready(function() {
        // Handle form submission for both create and update forms
        $('.form-submit').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var modalId = form.closest('.modal').attr('id');
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        // Close the modal
                        $('#' + modalId).modal('hide');

                        // Show success message
                        $('.alert').remove();
                        $('.card-body').prepend(
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            response.message +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>'
                        );
                        // Simpan posisi scroll sebelum reload
                        const scrollPosition = $(window).scrollTop();

                        // Setelah reload, kembalikan ke posisi sebelumnya
                        setTimeout(function() {
                            window.location.href = "<?= base_url('sub_kriteria?jenis_kelamin=') ?>" + response.jenis_kelamin + "&scroll=" + scrollPosition;
                        }, 1500);
                    } else {
                        // Show error message inside the modal
                        $('#' + modalId + ' .modal-body').prepend(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle JSON parse error
                    if (xhr.responseText && xhr.responseText.startsWith('<!DOCTYPE')) {
                        alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                    } else {
                        alert('Terjadi kesalahan: ' + error);
                    }
                }
            });
        });

        // Clear validation errors when modal is closed
        $('.modal').on('hidden.bs.modal', function() {
            $(this).find('.alert-danger').remove();
        });
    });
</script>
<script>
    $(document).ready(function() {
        const scrollPos = new URLSearchParams(window.location.search).get("scroll");
        if (scrollPos) {
            $(window).scrollTop(scrollPos);
        }
    });
</script>