<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-line"></i> Detail Hasil Pemijahan</h1>

    <!-- Tombol Cetak untuk Penyuluh -->
    <?php if ($id_user_level == 2): ?>
        <div class="d-flex">
            <?php
            $waktu_pemijahan = '';
            if (!empty($detail) && isset($detail[0]->waktu_pemijahan)) {
                $waktu_pemijahan = $detail[0]->waktu_pemijahan;
            } elseif (!empty($manual) && isset($manual->waktu_pemijahan)) {
                $waktu_pemijahan = $manual->waktu_pemijahan;
            } elseif (!empty($spk) && isset($spk->waktu_pemijahan)) {
                $waktu_pemijahan = $spk->waktu_pemijahan;
            }
            ?>
            <a href="<?= base_url('Hasilpmj/cetak_laporan?waktu_pemijahan=' . $waktu_pemijahan . '&upr_id=' . $selected_upr) ?>" class="btn btn-primary mr-2" target="_blank">
                <i class="fa fa-print"></i> Cetak Data
            </a>
            <a href="<?= base_url('Hasilpmj/index'); ?>" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    <?php endif; ?>

    <!-- Tombol Cetak untuk UPR -->
    <?php if ($id_user_level == 3): ?>
        <div class="d-flex">
            <?php
            $waktu_pemijahan = '';
            if (!empty($detail) && isset($detail[0]->waktu_pemijahan)) {
                $waktu_pemijahan = $detail[0]->waktu_pemijahan;
            } elseif (!empty($manual) && isset($manual->waktu_pemijahan)) {
                $waktu_pemijahan = $manual->waktu_pemijahan;
            } elseif (!empty($spk) && isset($spk->waktu_pemijahan)) {
                $waktu_pemijahan = $spk->waktu_pemijahan;
            }
            ?>
            <a href="<?= base_url('Hasilpmj/cetak_laporan?waktu_pemijahan=' . $waktu_pemijahan . '&upr_id=' . $selected_upr) ?>" class="btn btn-primary mr-2" target="_blank">
                <i class="fa fa-print"></i> Cetak Data
            </a>

            <a href="<?= base_url('Hasilpmj/index'); ?>" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Informasi Umum Pemijahan -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (!empty($item_pemijahan)): ?>
            <h6 class="text-primary"><strong>Detail Waktu Pemijahan</strong></h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Metode Pemijahan</th>
                            <td><?= $item_pemijahan->metode_pemijahan ?></td>
                        </tr>
                        <tr>
                            <th>Kolam</th>
                            <td><?= $item_pemijahan->kolam ?></td>
                        </tr>
                        <tr>
                            <th>Waktu Pemijahan</th>
                            <td><?= $item_pemijahan->waktu_pemijahan ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengisian</th>
                            <td><?= $item_pemijahan->created_at ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">Tidak ada data pemijahan.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Detail Hasil Pemijahan SPK -->
<?php if (!empty($spk)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Hasil Pemijahan SPK</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Induk</th>
                            <th>Jenis Kelamin</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($induk_spk as $induk): ?>
                            <tr>
                                <td><?= $induk->nama ?></td>
                                <td><?= $induk->jenis_kelamin ?></td>
                                <td><?= $induk->nilai ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #6495ED; color: white;">
                        <tr align="center">
                            <th>Jumlah Telur</th>
                            <th>Tingkat Penetasan (%)</th>
                            <th>Jumlah Benih</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td><?= $spk->jumlah_telur ?> telur</td>
                            <td><?= $spk->tingkat_netas ?>%</td>
                            <td><?= $spk->jumlah_benih ?> ekor</td>
                            <td><?= $spk->ket ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Detail Hasil Pemijahan Manual -->
<?php if (!empty($induk_manual)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Hasil Pemijahan Manual</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Induk</th>
                            <th>Kolam Induk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($induk_manual as $item): ?>
                            <tr align="center">
                                <td><?= $item->induk ?></td>
                                <td><?= $item->kolam_induk ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #6495ED; color: white;">
                        <tr align="center">
                            <th>Jumlah Telur</th>
                            <th>Tingkat Penetasan (%)</th>
                            <th>Jumlah Benih</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $item = reset($induk_manual); ?>
                        <tr align="center">
                            <td><?= $item->jumlah_telur ?> telur</td>
                            <td><?= $item->tingkat_netas ?>%</td>
                            <td><?= $item->jumlah_benih ?> ekor</td>
                            <td><?= $item->ket ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>