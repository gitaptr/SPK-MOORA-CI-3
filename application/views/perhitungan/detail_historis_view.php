<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-history"></i> Data Historis</h1>
    <div class="btn-group">
        <a href="<?= base_url('Perhitungan/cetak_laporanh/' . $pemijahan->id_pemijahan); ?>"
            class="btn btn-primary mr-2" target="_blank">
            <i class="fa fa-print"></i> Cetak Data
        </a>


        <a href="<?= base_url('Perhitungan/data_historis'); ?>" class="btn btn-secondary btn-icon-split mr-2"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
            <span class="text">Kembali</span>
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"></i> Detail Data Historis Hasil Perhitungan</h6>
    </div>
    <div class="card-body">


        <!-- Informasi Historis -->
        <?php if (!empty($detail)) : ?>
            <div class="row">
                <!-- Kolom Kiri (4 Data) -->
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Metode Pemijahan</th>
                                <td><?= htmlspecialchars($detail_pemijahan->metode_pemijahan, ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                            <tr>
                                <th>Kolam</th>
                                <td><?= htmlspecialchars($detail_pemijahan->kolam, ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                            <tr>
                                <th>Waktu Pemijahan</th>
                                <td><?= date('d-m-Y', strtotime($detail_pemijahan->waktu_pemijahan)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Kolom Kanan (3 Data) -->
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Tanggal Simpan</th>
                                <td><?= date('d-m-Y', strtotime($detail[0]->created_at ?? '')) ?></td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td><?= $detail[0]->keterangan ?? 'Tidak ada keterangan' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <p>Data historis tidak ditemukan.</p>
        <?php endif; ?>


        <!-- Hasil Perhitungan Detail Historis -->

        <!-- Informasi Kriteria Induk Jantan -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info"><i></i>🐟Induk Jantan</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($kriterias_jantan)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color:rgb(255, 255, 255);">
                                <tr align="center">
                                    <?php foreach ($kriterias_jantan as $kriteria): ?>
                                        <th><?= htmlspecialchars($kriteria->keterangan, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($kriteria->jenis, ENT_QUOTES, 'UTF-8') ?>)</th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr align="center">
                                    <?php foreach ($kriterias_jantan as $kriteria): ?>
                                        <td><?= htmlspecialchars($kriteria->bobot, ENT_QUOTES, 'UTF-8') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">Tidak ada data kriteria untuk induk jantan.</p>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-info text-white">
                            <tr align="center">
                                <th>Alternatif</th>
                                <?php foreach ($kriterias_jantan as $kriteria): ?>
                                    <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach; ?>
                                <th>Nilai Yi</th>
                                <!-- <th>Status</th>
                                        <th>Waktu Disimpan</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail_jantan as $data): ?>
                                <tr align="center">
                                    <td><?= $data->nama ?></td>
                                    <?php foreach ($kriterias_jantan as $kriteria): ?>
                                        <td><?= $nilai_kriteria[$data->id_alternatif][$kriteria->kode_kriteria] ?? '-' ?></td>
                                    <?php endforeach; ?>
                                    <td><?= $data->nilai ?></td>
                                    <!-- <td class="text-success"><b>Dipilih</b></td>
                                            <td><?= date('d-m-Y H:i:s', strtotime($data->created_at)) ?></td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Informasi Kriteria Induk Betina -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info"><i></i>🐟Induk Betina</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($kriterias_betina)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color:rgb(255, 255, 255);">
                                <tr align="center">
                                    <?php foreach ($kriterias_betina as $kriteria): ?>
                                        <th><?= htmlspecialchars($kriteria->keterangan, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($kriteria->jenis, ENT_QUOTES, 'UTF-8') ?>)</th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr align="center">
                                    <?php foreach ($kriterias_betina as $kriteria): ?>
                                        <td><?= htmlspecialchars($kriteria->bobot, ENT_QUOTES, 'UTF-8') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">Tidak ada data kriteria untuk induk betina.</p>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-info text-white">
                            <tr align="center">
                                <th>Alternatif</th>
                                <?php foreach ($kriterias_betina as $kriteria): ?>
                                    <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach; ?>
                                <th>Nilai Yi</th>
                                <!-- <th>Rank</th> -->
                                <!-- <th>Status</th>
                                        <th>Waktu Disimpan</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1;
                            foreach ($detail_betina as $data): ?>
                                <tr align="center">
                                    <td><?= $data->nama ?></td>
                                    <?php foreach ($kriterias_betina as $kriteria): ?>
                                        <td><?= $nilai_kriteria[$data->id_alternatif][$kriteria->kode_kriteria] ?? '-' ?></td>
                                    <?php endforeach; ?>
                                    <td><?= $data->nilai ?></td>
                                    <!-- <td><?= $rank++ ?></td>
                                            <td class="text-success"><b>Dipilih</b></td>
                                            <td><?= date('d-m-Y H:i:s', strtotime($data->created_at)) ?></td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($detail_jantan) && empty($detail_betina)): ?>
                    <div class="alert alert-warning text-center">Data historis tidak tersedia.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php $this->load->view('layouts/footer_admin'); ?>