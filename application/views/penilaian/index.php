<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-check-circle"></i> Data Penilaian</h1>
</div>

<?= $this->session->flashdata('message'); ?>

<!-- Form Pilihan Waktu Pemijahan -->
<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-info"></i> Pilih Waktu Pemijahan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('penilaian'); ?>" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <select name="id_pemijahan" id="id_pemijahan" class="form-control">
                        <option value="">-- Semua Waktu Pemijahan --</option>
                        <?php foreach ($pemijahan_list as $pemijahan): ?>
                            <option value="<?= $pemijahan->id_pemijahan; ?>" <?= ($id_pemijahan == $pemijahan->id_pemijahan) ? 'selected' : ''; ?>>
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


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Penilaian</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Nama Alternatif</th>
                        <th>Kolam</th>
                        <th>Jenis Kelamin</th>
                        <th width="15%">Aksi</th>
                </thead>
                <tbody>
                    <?php if (!empty($alternatif)): ?>
                        <?php foreach ($alternatif as $index => $alt): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= $alt->nama; ?></td>
                                <td><?= $alt->kolam; ?></td>
                                <td><?= $alt->jenis_kelamin; ?></td>
                                <td align="center">
                                    <?php
                                    // Menggunakan $alt->id_alternatif untuk memeriksa tombol
                                    $cek_tombol = $this->Penilaian_model->untuk_tombol($alt->id_alternatif);
                                    ?>
                                    <?php if ($cek_tombol == 0): ?>
                                        <!-- Tombol Input -->
                                        <a data-toggle="modal" href="#set<?= $alt->id_alternatif ?>" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Input
                                        </a>
                                    <?php else: ?>
                                        <!-- Tombol Edit -->
                                        <a data-toggle="modal" href="#edit<?= $alt->id_alternatif ?>" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data alternatif untuk waktu pemijahan yang dipilih.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($alternatif)): ?>
    <?php foreach ($alternatif as $keys): ?>
        <!-- Modal Input -->
        <div class="modal fade" id="set<?= $keys->id_alternatif ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Input Penilaian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <?= form_open('Penilaian/tambah_penilaian') ?>
                    <div class="modal-body">
                        <input type="hidden" name="id_pemijahan" value="<?= $id_pemijahan; ?>">
                        <input type="hidden" name="id_alternatif" value="<?= $keys->id_alternatif ?>">

                        <?php foreach ($keys->kriteria as $key): ?>
                            <?php $sub_kriteria = $this->Penilaian_model->get_sub_kriteria_by_gender($key->id_kriteria, $keys->jenis_kelamin); ?>
                            <?php if ($sub_kriteria != NULL): ?>
                                <input type="hidden" name="id_kriteria[]" value="<?= $key->id_kriteria ?>">
                                <div class="form-group">
                                    <label class="font-weight-bold" for="<?= $key->id_kriteria ?>"><?= $key->keterangan ?></label>
                                    <select name="nilai[]" class="form-control" id="<?= $key->id_kriteria ?>" required>
                                        <option value="">--Pilih--</option>
                                        <?php foreach ($sub_kriteria as $subs_kriteria): ?>
                                            <option value="<?= $subs_kriteria->id_sub_kriteria ?>"><?= $subs_kriteria->deskripsi ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="edit<?= $keys->id_alternatif ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edit Penilaian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <?= form_open('Penilaian/update_penilaian') ?>
                    <div class="modal-body">
                        <input type="hidden" name="id_pemijahan" value="<?= $id_pemijahan; ?>">
                        <input type="hidden" name="id_alternatif" value="<?= $keys->id_alternatif ?>">

                        <?php foreach ($keys->kriteria as $key): ?>
                            <?php $sub_kriteria = $this->Penilaian_model->get_sub_kriteria_by_gender($key->id_kriteria, $keys->jenis_kelamin); ?>
                            <?php if ($sub_kriteria != NULL): ?>
                                <input type="hidden" name="id_kriteria[]" value="<?= $key->id_kriteria ?>">
                                <div class="form-group">
                                    <label class="font-weight-bold" for="<?= $key->id_kriteria ?>"><?= $key->keterangan ?></label>
                                    <select name="nilai[]" class="form-control" id="<?= $key->id_kriteria ?>" required>
                                        <option value="">--Pilih--</option>
                                        <?php foreach ($sub_kriteria as $subs_kriteria): ?>
                                            <?php $s_option = $this->Penilaian_model->data_penilaian($keys->id_alternatif, $subs_kriteria->id_kriteria); ?>
                                            <option value="<?= $subs_kriteria->id_sub_kriteria ?>"
                                                <?php if ($subs_kriteria->id_sub_kriteria == @$s_option['nilai']) echo "selected"; ?>>
                                                <?= $subs_kriteria->deskripsi ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>


    <script>
        $(document).ready(function() {
            $('#btn-pilih').on('click', function() {
                var waktuPemijahanId = $('#waktu_pemijahan').val();

                if (waktuPemijahanId === '') {
                    alert('Silakan pilih waktu pemijahan terlebih dahulu!');
                    return;
                }

                // AJAX request untuk mendapatkan data alternatif
                $.ajax({
                    url: "<?= base_url('Penilaian/getAlternatifByWaktu') ?>", // Sesuaikan dengan URL controller Anda
                    method: 'POST',
                    data: {
                        id_pemijahan: waktuPemijahanId
                    },
                    dataType: 'json',
                    success: function(response) {
                        var tbody = $('#data-alternatif');
                        tbody.empty();

                        if (response.length > 0) {
                            $.each(response, function(index, alternatif) {
                                var tombolAksi = alternatif.cek_tombol == 0 ?
                                    `<a data-toggle="modal" href="#set${alternatif.id_alternatif}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Input</a>` :
                                    `<a data-toggle="modal" href="#edit${alternatif.id_alternatif}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>`;

                                tbody.append(`
                                <tr align="center">
                                    <td>${index + 1}</td>
                                    <td align="left">${alternatif.nama}</td>
                                    <td>${tombolAksi}</td>
                                </tr>
                            `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="3" align="center">Data tidak ditemukan</td></tr>');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil data alternatif.');
                    }
                });
            });
        });
    </script>

    <?php $this->load->view('layouts/footer_admin'); ?>