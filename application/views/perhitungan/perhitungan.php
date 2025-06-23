<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calculator"></i> Data Perhitungan</h1>
</div>

<!-- Form Pilihan Waktu Pemijahan dan Jenis Kelamin -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-calendar"></i> Pilih Waktu Pemijahan dan Jenis Kelamin</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('Perhitungan/hitung_moora'); ?>">
            <div class="row">
                <div class="col-md-5">
                    <select class="form-control" id="id_pemijahan" name="id_pemijahan" required>
                        <option value="">-- Pilih Waktu Pemijahan --</option>
                        <?php foreach ($waktu_pemijahan as $waktu): ?>
                            <option value="<?= $waktu->id_pemijahan ?>" <?= ($waktu->id_pemijahan == $id_pemijahan) ? 'selected' : '' ?>>
                                <?= date('d-m-Y', strtotime($waktu->waktu_pemijahan)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Jantan" <?= ($jenis_kelamin == 'Jantan') ? 'selected' : '' ?>>Jantan</option>
                        <option value="Betina" <?= ($jenis_kelamin == 'Betina') ? 'selected' : '' ?>>Betina</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Hitung</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if (!isset($id_pemijahan)) {
    $id_pemijahan = ''; // Berikan nilai default
}
?>

<?php if (!$id_pemijahan): ?>
    <div class="alert alert-warning mt-4">
        <strong>Silakan pilih waktu pemijahan untuk melihat data perhitungan.</strong>
    </div>
<?php elseif (empty($alternatifs)): ?>
    <div class="alert alert-danger mt-4">
        <strong>Tidak ada alternatif yang sesuai untuk waktu pemijahan yang dipilih.</strong>
    </div>
<?php else: ?>
    <!-- Matrix Keputusan (X) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Matrix Keputusan (X)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Nama Alternatif</th>
                            <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php foreach ($kriterias as $kriteria): ?>
                                    <td><?= $matriks_x[$kriteria->id_kriteria][$alternatif->id_alternatif] ?></td>
                                <?php endforeach ?>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bobot Preferensi (W) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Bobot Preferensi (W)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?> (<?= $kriteria->jenis ?>)</th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <?php foreach ($kriterias as $kriteria): ?>
                                <td><?= $kriteria->bobot ?></td>
                            <?php endforeach ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Matriks Ternormalisasi (R) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Matriks Ternormalisasi (R)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Nama Alternatif</th>
                            <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php foreach ($kriterias as $kriteria): ?>
                                    <td><?= round($matriks_r[$kriteria->id_kriteria][$alternatif->id_alternatif], 4) ?></td>
                                <?php endforeach ?>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Matriks Normalisasi Terbobot -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Matriks Normalisasi Terbobot</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Nama Alternatif</th>
                            <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php foreach ($kriterias as $kriteria): ?>
                                    <td><?= round($matriks_rb[$kriteria->id_kriteria][$alternatif->id_alternatif], 4) ?></td>
                                <?php endforeach ?>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Nilai Yi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Nilai Yi (Hasil Akhir)</h6>
			<a href="<?= base_url('Perhitungan/hasil?id_pemijahan='.$id_pemijahan.'&jenis_kelamin='.$jenis_kelamin) ?>" class="btn btn-primary btn-sm float-right">
                <i class="fas fa-fw fa-chart-area"></i> Lihat Detail Hasil Akhir </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-info text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Nama Alternatif</th>
                            <th>Benefit (Max)</th>
                            <th>Cost (Min)</th>
                            <th>Yi (Max - Min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatifs as $alternatif): ?>
                            <?php
                            $total_max = 0;
                            $total_min = 0;
                            foreach ($kriterias as $kriteria):
                                $nilai_rb = $matriks_rb[$kriteria->id_kriteria][$alternatif->id_alternatif];
                                if ($kriteria->jenis == "Benefit") {
                                    $total_max += $nilai_rb;
                                } else {
                                    $total_min += $nilai_rb;
                                }
                            endforeach;
                            $hasil_yi = $total_max - $total_min;
                            ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <td><?= round($total_max, 4) ?></td>
                                <td><?= round($total_min, 4) ?></td>
                                <td><?= round($hasil_yi, 4) ?></td>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($this->session->flashdata('success')) : ?>
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "<?= $this->session->flashdata('success') ?>",
            });
        <?php elseif ($this->session->flashdata('error')) : ?>
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "<?= $this->session->flashdata('error') ?>",
            });
        <?php endif; ?>
    });
</script>

<?php 
    // Mencegah flashdata muncul dua kali saat reload atau kembali ke halaman ini
    $this->session->unset_userdata('success');
    $this->session->unset_userdata('error');
?>

<?php $this->load->view('layouts/footer_admin'); ?>