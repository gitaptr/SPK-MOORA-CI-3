<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-random"></i> Data Alternatif</h1>


</div>

<?= $this->session->flashdata('message'); ?>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Pengisian calon induk ikan yang akan digunakan dalam pemijahan.</strong>
    <ul class="mt-2 mb-0">
        <li>Memilih <strong>waktu pemijahan</strong> terlebih dahulu dan tekan tombol <strong>Pilih</strong>.</li>
        <li>Setelah memilih waktu pemijahan tombol Tambah Data akan aktif.</li>
        <li>Lanjut tekan tombol <strong>Tambah Data Alternatif</strong> dan mengisi data calon induk.</li>
        <li>Pastikan data yang diisi benar dan tekan tombol <strong>Simpan</strong>.</li>
    </ul>
</div>



<!-- Form untuk memilih waktu pemijahan -->
<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-info"></i> Pilih Waktu Pemijahan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('alternatif/set_waktu_pemijahan') ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="id_pemijahan" name="id_pemijahan" required>
                        <option value="">--Semua Waktu Pemijahan--</option> <!-- Opsi untuk semua -->
                        <?php foreach ($pemijahan_list as $pemijahan): ?>
                            <option value="<?= $pemijahan->id_pemijahan ?>" <?= ($selected_id_pemijahan == $pemijahan->id_pemijahan) ? 'selected' : '' ?>>
                                <?= date('d-m-Y', strtotime($pemijahan->waktu_pemijahan)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Pilih</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Alternatif -->
<!-- Data Alternatif -->
<div class="card shadow mb-4">
    <div class="card-header py-2">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Alternatif</h6>
            <button class="btn btn-success" data-toggle="modal" data-target="#modal-Input"
                <?= !$this->session->userdata('id_pemijahan') ? 'disabled' : '' ?>>
                Tambah Data Alternatif
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?php if ($selected_id_pemijahan): ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th>No</th>
                            <th>Nama Alternatif</th>
                            <th>Kolam</th>
                            <th>Jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($list as $data => $value): ?>
                                <tr align="center">
                                    <td><?= $no ?></td>
                                    <td align="left"><?= $value->nama ?></td>
                                    <td align="left"><?= $value->kolam ?></td>
                                    <td><?= $value->jenis_kelamin ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-warning btn-sm" onclick="showEditModal(<?= $value->id_alternatif ?>)" title="Edit Data"><i class="fa fa-edit"></i></a>
                                            <a href="<?= base_url('Alternatif/destroy/' . $value->id_alternatif) ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus data ini?')" class="btn btn-danger btn-sm" title="Hapus Data"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php $no++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" align="center">
                                    Tidak ada data alternatif untuk waktu pemijahan yang dipilih.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">
                    Silakan pilih waktu pemijahan untuk menampilkan data alternatif.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Form Tambah Alternatif -->
<div class="modal fade" id="modal-Input" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Alternatif</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('Alternatif/store') ?>" novalidate>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Alternatif</label>
                        <input autocomplete="off" type="text" name="nama" required class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kolam</label>
                        <select name="kolam" class="form-control" required>
                            <option value="">--Pilih Kolam--</option>
                            <?php if (!empty($kolam_list)): ?>
                                <?php foreach ($kolam_list as $kolam): ?>
                                    <option value="<?= $kolam->kode_kolam ?>"><?= $kolam->kode_kolam ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Kolam tidak tersedia</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
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

<!-- Form Edit Alternatif -->
<div class="modal fade" id="modal-Edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEdit" method="post" action="<?= base_url('Alternatif/update') ?>" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Alternatif</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_alternatif" id="edit_id_alternatif" />
                    <div class="form-group">
                        <label for="edit_nama_ikan">Nama Alternatif</label>
                        <input type="text" class="form-control" name="nama" id="edit_nama" required />
                    </div>
                    <div class="form-group">
                        <label for="edit_kolam">Kolam</label>
                        <select name="kolam" class="form-control" id="edit_kolam" required>
                            <option value="" disabled selected>-- Pilih Kolam --</option>
                            <?php if (!empty($kolam_list)): ?>
                                <?php foreach ($kolam_list as $kolam): ?>
                                    <option value="<?= $kolam->kode_kolam ?>"><?= $kolam->kode_kolam ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Kolam tidak tersedia</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" id="edit_jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
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
        $.ajax({
            url: "<?= base_url('Alternatif/get_alternatif_by_id/') ?>" + id,
            type: 'GET',
            data: {
                id_alternatif: id
            },
            dataType: 'json',
            success: function(data) {
                // Isi data ke input form di modal
                $('#edit_id_alternatif').val(data.id_alternatif);
                $('#edit_nama').val(data.nama);
                $('#edit_kolam').val(data.kolam);
                $('#edit_jenis_kelamin').val(data.jenis_kelamin); // Isi jenis kelamin

                // Tampilkan modal
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