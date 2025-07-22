<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Kriteria</h1>

    <a href="<?= base_url('Kriteria'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
        <span class="text">Kembali</span>
    </a>
</div>

<?php if ($this->session->flashdata('message')) : ?>
    <?= $this->session->flashdata('message'); ?>
<?php endif; ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data Kriteria</h6>
    </div>

    <?php echo form_open('Kriteria/update/' . $kriteria->id_kriteria); ?>
    <div class="card-body">
        <div class="row">
            <?php echo form_hidden('id_kriteria', $kriteria->id_kriteria) ?>
            <div class="form-group col-md-6">
                <label class="font-weight-bold">Kode Kriteria (Otomatis)</label>
                <input type="text" class="form-control" name="kode_kriteria_display" id="kode_kriteria_display" value="<?= $kriteria->kode_kriteria ?>" readonly>
                <input type="hidden" name="kode_kriteria" value="<?= $kriteria->kode_kriteria ?>">
            </div>

            <div class="form-group col-md-6">
                <label class="font-weight-bold">Nama Kriteria</label>
                <input autocomplete="off" type="text" name="keterangan" value="<?php echo $kriteria->keterangan ?>" required class="form-control" />
            </div>

            <?php
            // Pastikan $kriteria->jenis_kelamin selalu ada saat edit
            if (!isset($kriteria) || empty($kriteria->jenis_kelamin)) {
                echo "<div class='col-md-12'><div class='alert alert-danger'>Error: Jenis kelamin kriteria tidak ditemukan.</div></div>";
            } else {
                echo form_hidden('jenis_kelamin', $kriteria->jenis_kelamin);
            }
            ?>
            <div class="form-group col-md-6">
                <label class="font-weight-bold">Bobot Kriteria</label>
                <input autocomplete="off" type="number" id="bobot_kriteria" name="bobot" step="0.01" value="<?php echo $kriteria->bobot ?>" required class="form-control" />
                <small id="bobot_kriteria_error" class="text-danger"></small>
            </div>

            <div class="form-group col-md-6">
                <label class="font-weight-bold">Jenis Kriteria</label>
                <select name="jenis" class="form-control" required>
                    <option value="Benefit" <?php if ($kriteria->jenis == "Benefit") {
                                                echo 'selected';
                                            } ?>>Benefit</option>
                    <option value="Cost" <?php if ($kriteria->jenis == "Cost") {
                                                echo 'selected';
                                            } ?>>Cost</option>
                </select>
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
    document.addEventListener('DOMContentLoaded', function() {
                const kodeKriteriaInput = document.getElementById('kode_kriteria');
                const kodeKriteriaError = document.getElementById('kode_kriteria_error');

                const bobotKriteriaInput = document.getElementById('bobot_kriteria');
                const bobotKriteriaError = document.getElementById('bobot_kriteria_error');

                const jenisKelaminDropdown = document.getElementById('jenis_kelamin');
                const submitButton = document.querySelector('button[type="submit"]');

                // Untuk edit.php, dapatkan nilai dari PHP
                const jenisKelamin = "<?= isset($kriteria->jenis_kelamin) ? $kriteria->jenis_kelamin : '' ?>";
                const excludeId = "<?= isset($kriteria->id_kriteria) ? $kriteria->id_kriteria : '' ?>";
                const bobotLama = <?= isset($kriteria->bobot) ? floatval($kriteria->bobot) : 0 ?>;

                // Fungsi validasi kode kriteria
                async function validateKodeKriteria() {
                    const kodeKriteria = kodeKriteriaInput.value.trim();
                    const currentJenisKelamin = jenisKelaminDropdown ? jenisKelaminDropdown.value : jenisKelamin;

                    if (!currentJenisKelamin) {
                        kodeKriteriaError.textContent = 'Pilih jenis kelamin terlebih dahulu.';
                        return false;
                    }

                    if (!kodeKriteria) {
                        kodeKriteriaError.textContent = 'Kode Kriteria tidak boleh kosong.';
                        return false;
                    }

                    try {
                        const response = await fetch(`<?= base_url('Kriteria/check_duplicate_kode') ?>?kode_kriteria=${kodeKriteria}&exclude_id=${excludeId}&jenis_kelamin=${currentJenisKelamin}`);
                        const data = await response.json();

                        if (data.is_duplicate) {
                            kodeKriteriaError.textContent = 'Kode Kriteria sudah digunakan.';
                            return false;
                        } else {
                            kodeKriteriaError.textContent = '';
                            return true;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        return false;
                    }
                }

                // Fungsi validasi bobot kriteria
                async function validateBobotKriteria() {
                    const bobotValue = bobotKriteriaInput.value.trim();
                    const bobot = parseFloat(bobotValue);
                    const currentJenisKelamin = jenisKelaminDropdown ? jenisKelaminDropdown.value : jenisKelamin;

                    if (!currentJenisKelamin) {
                        bobotKriteriaError.textContent = 'Pilih jenis kelamin terlebih dahulu.';
                        return false;
                    }

                    if (!bobotValue) {
                        bobotKriteriaError.textContent = 'Bobot Kriteria tidak boleh kosong.';
                        return false;
                    }

                    if (isNaN(bobot) {
                            bobotKriteriaError.textContent = 'Bobot harus berupa angka.';
                            return false;
                        }

                        if (bobot <= 0 || bobot > 1) {
                            bobotKriteriaError.textContent = 'Bobot harus antara 0.01 dan 1.0.';
                            return false;
                        }

                        try {
                            const response = await fetch(`<?= base_url('Kriteria/get_total_bobot_by_jenis_kelamin') ?>?jenis_kelamin=${currentJenisKelamin}`);
                            const data = await response.json();

                            const totalBobotSaatIni = parseFloat(data.total_bobot || 0);
                            const totalSetelahUpdate = (totalBobotSaatIni - bobotLama + bobot).toFixed(6);

                            if (parseFloat(totalSetelahUpdate) > 1.0) {
                                bobotKriteriaError.textContent = `Total bobot tidak boleh lebih dari 1.0 (Saat ini: ${totalSetelahUpdate})`;
                                return false;
                            } else {
                                bobotKriteriaError.textContent = '';
                                return true;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            return false;
                        }
                    }

                    // Event listeners untuk validasi real-time
                    if (kodeKriteriaInput) {
                        kodeKriteriaInput.addEventListener('input', async function() {
                            await validateKodeKriteria();
                            updateSubmitButton();
                        });
                    }

                    if (bobotKriteriaInput) {
                        bobotKriteriaInput.addEventListener('input', async function() {
                            await validateBobotKriteria();
                            updateSubmitButton();
                        });
                    }

                    if (jenisKelaminDropdown) {
                        jenisKelaminDropdown.addEventListener('change', async function() {
                            await validateKodeKriteria();
                            await validateBobotKriteria();
                            updateSubmitButton();
                        });
                    }

                    // Fungsi untuk mengupdate status tombol submit
                    function updateSubmitButton() {
                        const kodeValid = !kodeKriteriaError.textContent;
                        const bobotValid = !bobotKriteriaError.textContent;

                        if (submitButton) {
                            submitButton.disabled = !(kodeValid && bobotValid);
                        }
                    }

                    // Validasi awal saat halaman dimuat
                    if (excludeId) { // Jika edit
                        validateKodeKriteria();
                        validateBobotKriteria();
                        updateSubmitButton();
                    }
                });
</script>