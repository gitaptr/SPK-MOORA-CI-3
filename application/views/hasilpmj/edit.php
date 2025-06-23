<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Hasil Pemijahan</h1>
    <a href="<?= base_url('Hasilpmj'); ?>" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
        <span class="text">Kembali</span>
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data Hasil Pemijahan</h6>
    </div>

    <?php
    $hasilpmj = $hasilpmj ?? (object) [
        'waktu_pemijahan' => '',
        'kolam' => '',
        'metode_pemijahan' => '',
        'id_hasilpmj' => null,
        'id_hasilpmj_manual' => null
    ];

    $spk_data = $spk_data ?? [
        'jumlah_telur' => 0,
        'tingkat_netas' => 0,
        'jumlah_benih' => 0,
        'ket' => ''
    ];

    $manual_data = $manual_data ?? [
        'jumlah_telur' => 0,
        'tingkat_netas' => 0,
        'jumlah_benih' => 0,
        'ket' => ''
    ];

    $induk_spk = $induk_spk ?? [];
    $induk_manual = $induk_manual ?? [];

    $has_spk = !empty($induk_spk) || ($spk_data['jumlah_benih'] > 0);
    $has_manual = !empty($induk_manual) || ($manual_data['jumlah_benih'] > 0);
    ?>

    <?php echo form_open('Hasilpmj/update/' . $hasilpmj->waktu_pemijahan . '/' . $type); ?>
    <input type="hidden" name="has_spk" value="<?= $has_spk ? 1 : 0 ?>">
    <input type="hidden" name="has_manual" value="<?= $has_manual ? 1 : 0 ?>">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Waktu Pemijahan</label>
                <input type="date" name="waktu_pemijahan"
                    value="<?= ($type == 'spk') ? ($hasilpmj->waktu_pemijahan ?? '') : ($manual_data['waktu_pemijahan'] ?? '') ?>"
                    class="form-control" <?= ($type == 'spk') ? 'readonly' : '' ?> />
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Kolam</label>
                <input type="text" name="kolam"
                    value="<?= ($type == 'spk') ? ($hasilpmj->kolam ?? '') : ($manual_data['kolam'] ?? '') ?>"
                    class="form-control" <?= ($type == 'spk') ? 'readonly' : '' ?> />
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Metode Pemijahan</label>
                <input type="text" name="metode_pemijahan"
                    value="<?= ($type == 'spk') ? ($hasilpmj->metode_pemijahan ?? '') : ($manual_data['metode_pemijahan'] ?? '') ?>"
                    class="form-control" <?= ($type == 'spk') ? 'readonly' : '' ?> />
            </div>
        </div>

        <!-- Pilihan Induk -->
        <div class="form-group col-md-12">
            <label class="font-weight-bold">Pilih Induk</label>
            <ul class="nav nav-tabs" id="indukTab" role="tablist">
                <?php if ($has_spk): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type == 'spk') ? 'active' : '' ?>" id="spk-tab" data-toggle="tab" href="#spk" role="tab">Edit Induk SPK</a>
                    </li>
                <?php endif; ?>
                <?php if ($has_manual): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type == 'manual') ? 'active' : '' ?>" id="manual-tab" data-toggle="tab" href="#manual" role="tab">Edit Induk Manual</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content mt-2">
                <!-- Tab SPK -->
                <?php if ($has_spk): ?>
                    <div class="tab-pane fade <?= ($type == 'spk') ? 'show active' : '' ?>" id="spk" role="tabpanel">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Induk</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nilai SPK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($induk_spk)): ?>
                                    <?php foreach ($induk_spk as $induk): ?>
                                        <tr>
                                            <td><?= $induk->nama ?></td>
                                            <td><?= $induk->jenis_kelamin ?></td>
                                            <td><?= $induk->nilai ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data induk SPK</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Form SPK -->
                        <div class="row mt-3">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Jumlah Telur</label>
                                <input type="number" name="spk_jumlah_telur" value="<?= $spk_data['jumlah_telur'] ?? 0 ?>" class="form-control" required />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Tingkat Penetasan (%)</label>
                                <input type="number" name="spk_tingkat_netas" value="<?= $spk_data['tingkat_netas'] ?? 0 ?>" class="form-control" required min="0" max="100" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Jumlah Benih</label>
                                <input type="number" name="spk_jumlah_benih" value="<?= $spk_data['jumlah_benih'] ?? 0 ?>" class="form-control" required min="0" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Keterangan</label>
                                <input type="text" name="spk_keterangan" value="<?= $spk_data['ket'] ?? '' ?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tab Manual -->
                <?php if ($has_manual): ?>
                    <div class="tab-pane fade <?= ($type == 'manual') ? 'show active' : '' ?>" id="manual" role="tabpanel">
                        <div id="induk-manual-container">
                            <?php if (!empty($induk_manual)): ?>
                                <?php foreach ($induk_manual as $index => $induk): ?>
                                    <div class="induk-manual-item mb-3">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Nama Induk</label>
                                                <input type="text" name="induk_manual[<?= $index ?>][nama]"
                                                    value="<?= htmlspecialchars($induk->induk ?? '') ?>" class="form-control" readonly />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Kolam Induk</label>
                                                <input type="text" name="induk_manual[<?= $index ?>][kolam]"
                                                    value="<?= htmlspecialchars($induk->kolam_induk ?? '') ?>" class="form-control" readonly />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Jenis Kelamin</label>
                                                <input type="text" name="induk_manual[<?= $index ?>][jenis_kelamin]"
                                                    value="<?= htmlspecialchars($induk->jenis_kelamin ?? '') ?>" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info">Tidak ada data induk manual</div>
                            <?php endif; ?>
                        </div>

                        <!-- Form Manual -->
                        <div class="row mt-3">
                            <div class="form-group col-md-3">
                                <label>Jumlah Telur</label>
                                <input type="number" name="manual_jumlah_telur"
                                    value="<?= $manual_data['jumlah_telur'] ?? 0 ?>" class="form-control" required min="0" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tingkat Penetasan (%)</label>
                                <input type="number" name="manual_tingkat_netas"
                                    value="<?= $manual_data['tingkat_netas'] ?? 0 ?>" class="form-control" required min="0" max="100" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Jumlah Benih</label>
                                <input type="number" name="manual_jumlah_benih"
                                    value="<?= $manual_data['jumlah_benih'] ?? 0 ?>" class="form-control" required min="0" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Keterangan</label>
                                <input type="text" name="manual_keterangan"
                                    value="<?= $manual_data['ket'] ?? '' ?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
    </div>
    <?php echo form_close() ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>

<script>
    $(document).ready(function() {
        // Validasi form sebelum submit
        $('form').submit(function(e) {
            // Aktifkan semua tab sebelum submit
            $('.tab-pane').addClass('show active');
            
            const activeTab = $('.nav-tabs .nav-link.active').attr('id');
            let isValid = true;

            // Validasi tab SPK
            if ($('input[name="has_spk"]').val() == '1') {
                if ($('input[name="spk_jumlah_telur"]').val() === '' ||
                    $('input[name="spk_tingkat_netas"]').val() === '' ||
                    $('input[name="spk_jumlah_benih"]').val() === '') {
                    alert('Harap lengkapi semua field pada tab SPK!');
                    isValid = false;
                }
            }
            
            // Validasi tab Manual
            if ($('input[name="has_manual"]').val() == '1') {
                if ($('input[name="manual_jumlah_telur"]').val() === '' ||
                    $('input[name="manual_tingkat_netas"]').val() === '' ||
                    $('input[name="manual_jumlah_benih"]').val() === '') {
                    alert('Harap lengkapi semua field pada tab Manual!');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
            return true;
        });
    });
</script>