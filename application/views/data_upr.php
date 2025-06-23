<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-list"></i> Data-Data UPR
    </h1>

    <div class="d-flex">
        <?php if ($this->session->userdata('id_user_level') == 2): ?>
            <a href="<?= base_url('Data_upr/cetak_laporan') ?>" class="btn btn-primary mr-2" target="_blank">
                <i class="fa fa-print"></i> Cetak Laporan
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($page) && $page === 'pemijahan'): ?>
    <?= $this->session->flashdata('message'); ?>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info">Daftar Data-Data UPR</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UPR</th>
                        <th>Jumlah Kolam</th>
                        <th>Induk Betina</th>
                        <th>Induk Jantan</th>
                        <th>Total Benih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total = [
                        'kolam' => 0,
                        'betina' => 0,
                        'jantan' => 0,
                        'benih' => 0
                    ];

                    foreach ($list_upr as $upr):
                        $total['kolam'] += $upr->jumlah_kolam;
                        $total['betina'] += $upr->jumlah_induk_betina;
                        $total['jantan'] += $upr->jumlah_induk_jantan;
                        $total['benih'] += $upr->jumlah_benih;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($upr->nama_upr) ?></td>
                            <td class="text-center"><?= $upr->jumlah_kolam ?> kolam</td>
                            <td class="text-center"><?= $upr->jumlah_induk_betina ?> ekor</td>
                            <td class="text-center"><?= $upr->jumlah_induk_jantan ?> ekor</td>
                            <td class="text-center"><?= number_format($upr->jumlah_benih) ?> ekor</td>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="font-weight-bold bg-light text-center">
                        <td colspan="2">TOTAL</td>
                        <td><?= $total['kolam'] ?> kolam</td>
                        <td><?= $total['betina'] ?> ekor</td>
                        <td><?= $total['jantan'] ?> ekor</td>
                        <td><?= number_format($total['benih']) ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>