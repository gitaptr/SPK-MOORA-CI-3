<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-line"></i> Hasil Pemijahan</h1>
    <div class="d-flex">
        <?php
        $id_user_level = isset($id_user_level) ? $id_user_level : null;
        ?>

    </div>
</div>

<?= $this->session->flashdata('message'); ?>

<?php if ($this->session->userdata('id_user_level') == 2): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-calendar"></i> Pilih UPR</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('hasilpmj') ?>">
                <div class="form-group">
                    <select name="upr_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih UPR --</option>
                        <?php foreach ($upr_list as $upr) : ?>
                            <option value="<?= $upr->id_upr ?>" <?= ($selected_upr == $upr->id_upr) ? 'selected' : '' ?>>
                                <?= $upr->nama_upr ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info"></i> Daftar Hasil Akhir Pemijahan</h6>
        <?php if (!$read_only && $this->session->userdata('id_user_level') == 3): ?>
            <a href="<?= base_url('Hasilpmj/create'); ?>" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (!empty($selected_upr) || $this->session->userdata('id_user_level') == 3): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Waktu Pemijahan</th>
                            <th>Kolam</th>
                            <th>Metode Pemijahan</th>
                            <th>Tanggal Pengisian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($list as $data => $value) {
                        ?>
                            <tr align="center">
                                <td><?= $no ?></td>
                                <td><?php echo $value->waktu_pemijahan ?></td>
                                <td><?php echo $value->kolam ?></td>
                                <td><?php echo $value->metode_pemijahan ?></td>
                                <td><?php echo $value->created_at ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php
                                        $id_user_level = $this->session->userdata('id_user_level');
                                        $id_upr = $value->id_upr; // pastikan properti ini tersedia dalam $value
                                        ?>

                                        <!-- Tombol Detail -->
                                        <?php if ($id_user_level == 2): ?>
                                            <a href="<?= base_url('Hasilpmj/detail?waktu_pemijahan=' . $value->waktu_pemijahan . '&upr_id=' . $id_upr . '&type=' . (isset($value->id_hasilpmj) ? 'spk' : 'manual')) ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php
                                            $jenis = '';
                                            if (!empty($value->sumber) && $value->sumber == 'spk') {
                                                $jenis = 'spk';
                                            } elseif (!empty($value->sumber) && $value->sumber == 'manual') {
                                                $jenis = 'manual';
                                            }
                                            ?>

                                            <a href="<?= base_url('Hasilpmj/detail/' . $value->waktu_pemijahan) ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>


                                        <?php endif; ?>

                                        <!-- Tombol Edit/Hapus -->
                                        <?php if ($id_user_level == 3): ?>
                                            <!-- Untuk semua tipe data (SPK dan Manual) gunakan waktu_pemijahan -->
                                            <!-- Tombol Edit -->
                                            <a href="<?= base_url('hasilpmj/edit/' . $value->waktu_pemijahan . '/' . (isset($value->id_hasilpmj) ? 'spk' : 'manual')) ?>" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <a href="<?= base_url('hasilpmj/destroy/' . $value->waktu_pemijahan . '/' . (isset($value->id_hasilpmj) ? 'spk' : 'manual')) ?>" onclick="return confirm('Apakah anda yakin?')" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
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
        <?php else: ?>
            <p class="text-center text-muted">Silakan pilih UPR untuk melihat data hasil pemijahan.</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $this->session->flashdata('success') ?>',
                showConfirmButton: true,
                timer: 3000
            });
        <?php elseif ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $this->session->flashdata('error') ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    });
</script>

<script>
    $(document).ready(function() {
        <?php if ($swal = $this->session->flashdata('swal')): ?>
            // Cek apakah alert sudah pernah ditampilkan
            if (sessionStorage.getItem('swalShown') !== 'true') {
                Swal.fire({
                    icon: '<?= $swal['type'] ?>',
                    title: '<?= $swal['title'] ?>',
                    text: '<?= $swal['text'] ?>',
                    showConfirmButton: true,
                    timer: 3000
                });
                // Set flag di sessionStorage
                sessionStorage.setItem('swalShown', 'true');
            }
            <?php $this->session->unset_userdata('swal'); ?>
        <?php endif; ?>

        // Reset flag saat berpindah halaman
        $(window).on('beforeunload', function() {
            sessionStorage.removeItem('swalShown');
        });
    });
</script>