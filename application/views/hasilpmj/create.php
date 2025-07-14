<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Hasil Pemijahan</h1>
    <a href="<?= base_url('Hasilpmj'); ?>" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
        <span class="text">Kembali</span>
    </a>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-plus"></i> Tambah Data Hasil Pemijahan</h6>
    </div>

    <?php echo form_open('Hasilpmj/store'); ?>
    <input type="hidden" name="active_tab" id="active_tab" value="manual"> <!-- Default ke manual -->
    <input type="hidden" id="selected_waktu_pemijahan" name="waktu_pemijahan">

    <div class="card-body">
        <!-- Pilihan Induk -->
        <ul class="nav nav-tabs" id="indukTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="spk-tab" data-toggle="tab" href="#spk" role="tab">Induk SPK</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manual" role="tab">Induk Manual</a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <!-- Induk dari SPK -->
            <div class="tab-pane fade show active" id="spk" role="tabpanel">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Waktu Pemijahan</label>
                        <select id="waktu_pemijahan_spk" name="waktu_pemijahan_spk" class="form-control spk-field" required>
                            <option value="">-- Pilih Waktu Pemijahan (SPK) --</option>
                            <?php foreach ($pemijahan_list_spk as $pemijahan): ?>
                                <option value="<?= $pemijahan->waktu_pemijahan ?>">
                                    <?= $pemijahan->waktu_pemijahan ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Kolam</label>
                        <input type="text" id="kolam_spk" name="kolam_spk" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Metode Pemijahan</label>
                        <input type="text" id="metode_pemijahan_spk" name="metode_pemijahan_spk" class="form-control" readonly>
                    </div>
                </div>

                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Nama Induk</th>
                            <th>Jenis Kelamin</th>
                            <th>Nilai SPK</th>
                        </tr>
                    </thead>
                    <tbody id="list_induk_spk">
                        <tr>
                            <td colspan="3" class="text-center">Pilih waktu pemijahan terlebih dahulu</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Form Hasil Pemijahan untuk Induk SPK -->
                <div class="row spk-form">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Jumlah Telur</label>
                        <input type="number" name="jumlah_telur_spk" class="form-control" min="0">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Tingkat Penetasan (%)</label>
                        <input type="number" name="tingkat_netas_spk" class="form-control" min="0" max="100">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Jumlah Benih</label>
                        <input type="number" name="jumlah_benih_spk" class="form-control" min="0">
                        <small class="text-muted">Jumlah ini akan otomatis masuk ke stok benih</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Keterangan</label>
                        <input type="text" name="keterangan_spk" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Tambah Induk Manual -->
            <div class="tab-pane fade" id="manual" role="tabpanel">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Waktu Pemijahan</label>
                        <select id="waktu_pemijahan_manual" name="waktu_pemijahan_manual" class="form-control manual-field">
                            <option value="">-- Pilih Waktu Pemijahan --</option>
                            <?php foreach ($pemijahan_list_manual as $pemijahan): ?>
                                <option value="<?= $pemijahan->waktu_pemijahan ?>">
                                    <?= $pemijahan->waktu_pemijahan ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" id="waktu_pemijahan_manual_display" class="form-control" style="display:none;" readonly>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Kolam</label>
                        <input type="text" id="kolam_manual" name="kolam_manual" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Metode Pemijahan</label>
                        <input type="text" id="metode_pemijahan_manual" name="metode_pemijahan_manual" class="form-control" readonly>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-3">
                        <label>Nama Induk</label>
                        <input type="text" id="nama_induk_manual" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Kolam Induk</label>
                        <select id="kolam_induk_manual" class="form-control">
                            <option value="">-- Pilih Kolam --</option>
                            <?php foreach ($kolam_list as $kolam): ?>
                                <option value="<?= $kolam->kode_kolam ?>"><?= $kolam->kode_kolam ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jenis Kelamin</label>
                        <select id="jenis_kelamin_manual" class="form-control">
                            <option value="Betina">Betina</option>
                            <option value="Jantan">Jantan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="button" id="add_induk_manual" class="btn btn-info btn-block">Tambah</button>
                    </div>
                </div>

                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Nama Induk</th>
                            <th>Kolam</th>
                            <th>Jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list_induk_manual"></tbody>
                </table>

                <!-- Form Hasil Pemijahan untuk Induk Manual -->
                <div class="row manual-form">
                    <div class="form-group col-md-3">
                        <label>Jumlah Telur</label>
                        <input type="number" name="jumlah_telur_manual" class="form-control" min="0">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tingkat Penetasan (%)</label>
                        <input type="number" name="tingkat_netas_manual" class="form-control" min="0" max="100">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Jumlah Benih</label>
                        <input type="number" name="jumlah_benih_manual" class="form-control" min="0">
                        <small class="text-muted">Jumlah ini akan otomatis masuk ke stok benih</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan_manual" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> Simpan Data
        </button>
        <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
    </div>
    <?php echo form_close(); ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>

<script>
    $(document).ready(function() {
        // Inisialisasi variabel
        let activeTab = 'spk'; // Default tab aktif
        let waktuFromSPK = false; // Flag untuk menandai waktu dari SPK

        // Tangani perubahan tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            activeTab = $(e.target).attr("href").replace('#', '');
            $('#active_tab').val(activeTab);

            if (activeTab === 'spk') {
                $('.spk-field').show().prop('required', true);
                $('.manual-field').hide();
                $('#waktu_pemijahan_manual_display').hide();

                // Validasi: pastikan wajib diisi untuk SPK
                $('input[name="jumlah_benih_spk"]').prop('required', true);
                $('input[name="jumlah_benih_manual"]').prop('required', false);

                // Jika waktu dari SPK sudah dipilih, sync ke manual
                if ($('#waktu_pemijahan_spk').val()) {
                    syncWaktuPemijahan();
                }
            } else {
                $('.spk-field').hide().prop('required', false);

                // Jika waktu berasal dari SPK, tampilkan display field
                if (waktuFromSPK) {
                    $('#waktu_pemijahan_manual').hide().prop('required', false);
                    $('#waktu_pemijahan_manual_display').show();
                } else {
                    $('#waktu_pemijahan_manual').show().prop('required', true);
                    $('#waktu_pemijahan_manual_display').hide();
                }

                // Validasi: pastikan wajib diisi untuk Manual
                $('input[name="jumlah_benih_spk"]').prop('required', false);
                $('input[name="jumlah_benih_manual"]').prop('required', true);
            }
        });

        // Fungsi untuk sinkronisasi waktu pemijahan dari SPK ke Manual
        function syncWaktuPemijahan() {
            const waktuSPK = $('#waktu_pemijahan_spk').val();
            if (waktuSPK) {
                $('#waktu_pemijahan_manual').val(waktuSPK);
                $('#waktu_pemijahan_manual_display').val(waktuSPK);
                $('#selected_waktu_pemijahan').val(waktuSPK);
                waktuFromSPK = true;

                // Jika di tab manual, update tampilan
                if (activeTab === 'manual') {
                    $('#waktu_pemijahan_manual').hide();
                    $('#waktu_pemijahan_manual_display').show();
                }
            }
        }

        // Fungsi untuk reset waktu manual ketika memilih waktu manual
        function resetWaktuManual() {
            waktuFromSPK = false;
            $('#waktu_pemijahan_manual').show().val('');
            $('#waktu_pemijahan_manual_display').hide().val('');
            $('#selected_waktu_pemijahan').val('');

            // Reset kolam dan metode pemijahan
            $('#kolam_manual').val('');
            $('#metode_pemijahan_manual').val('');
        }

        // Fungsi untuk mengambil detail pemijahan
        function getPemijahanDetails(waktuPemijahan, callback) {
            $.ajax({
                url: "<?= base_url('Hasilpmj/get_pemijahan_details') ?>",
                type: "POST",
                data: {
                    waktu_pemijahan: waktuPemijahan
                },
                dataType: "json",
                success: callback,
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal mengambil data pemijahan');
                }
            });
        }

        // Handle perubahan dropdown SPK
        $('#waktu_pemijahan_spk').change(function() {
            const waktuPemijahan = $(this).val();
            $('#selected_waktu_pemijahan').val(waktuPemijahan);

            if (waktuPemijahan) {
                // Sync ke tab manual
                syncWaktuPemijahan();

                getPemijahanDetails(waktuPemijahan, function(response) {
                    $('#kolam_spk').val(response.kolam);
                    $('#metode_pemijahan_spk').val(response.metode_pemijahan);
                    $('#kolam_manual').val(response.kolam);
                    $('#metode_pemijahan_manual').val(response.metode_pemijahan);

                    // Ambil data induk SPK
                    $.ajax({
                        url: "<?= base_url('Hasilpmj/get_induk_spk') ?>",
                        type: "POST",
                        data: {
                            waktu_pemijahan: waktuPemijahan
                        },
                        dataType: "json",
                        success: function(response) {
                            let html = "";
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(function(row) {
                                    html += `<tr>
                                        <td>${row.nama}</td>
                                        <td>${row.jenis_kelamin}</td>
                                        <td>${row.nilai}</td>
                                    </tr>`;
                                });
                            } else {
                                html = "<tr><td colspan='3' class='text-center'>Tidak ada data induk SPK</td></tr>";
                            }
                            $('#list_induk_spk').html(html);
                        }
                    });
                });
            } else {
                $('#kolam_spk').val('');
                $('#metode_pemijahan_spk').val('');
                $('#list_induk_spk').html('<tr><td colspan="3" class="text-center">Pilih waktu pemijahan terlebih dahulu</td></tr>');
                resetWaktuManual();
            }
        });

        // Handle perubahan dropdown Manual
        $('#waktu_pemijahan_manual').change(function() {
            const waktuPemijahan = $(this).val();
            $('#selected_waktu_pemijahan').val(waktuPemijahan);

            if (waktuPemijahan) {
                // Reset flag waktu dari SPK
                waktuFromSPK = false;

                getPemijahanDetails(waktuPemijahan, function(response) {
                    $('#kolam_manual').val(response.kolam);
                    $('#metode_pemijahan_manual').val(response.metode_pemijahan);
                });
            } else {
                $('#kolam_manual').val('');
                $('#metode_pemijahan_manual').val('');
            }
        });

        // Tambah induk manual
        $('#add_induk_manual').click(function() {
            let namaInduk = $('#nama_induk_manual').val().trim();
            let kolam = $('#kolam_induk_manual').val();
            let jenisKelamin = $('#jenis_kelamin_manual').val();

            if (!namaInduk || !kolam) {
                alert('Silakan isi Nama Induk dan Kolam sebelum menambahkan.');
                return;
            }

            // Validasi apakah waktu pemijahan sudah dipilih
            if (!$('#selected_waktu_pemijahan').val()) {
                alert('Pilih waktu pemijahan terlebih dahulu!');
                return;
            }

            let row = `<tr>
                <td>${namaInduk}</td>
                <td>${kolam}</td>
                <td>${jenisKelamin}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-induk">Hapus</button>
                    <input type="hidden" name="induk_manual_nama[]" value="${namaInduk}">
                    <input type="hidden" name="induk_manual_kolam[]" value="${kolam}">
                    <input type="hidden" name="induk_manual_jenis_kelamin[]" value="${jenisKelamin}">
                </td>
            </tr>`;
            $('#list_induk_manual').append(row);

            // Reset input
            $('#nama_induk_manual').val('');
            $('#kolam_induk_manual').val('');
        });

        // Hapus induk manual
        $(document).on('click', '.remove-induk', function() {
            $(this).closest('tr').remove();
        });

        // Perbaikan validasi form
        // Perbaikan validasi form
        $('form').on('submit', function(e) {
            // 1. Validasi waktu pemijahan (wajib untuk semua)
            if (!$('#selected_waktu_pemijahan').val()) {
                alert('Pilih waktu pemijahan terlebih dahulu!');
                e.preventDefault();
                return false;
            }

            // 2. Ambil nilai dari form
            const jumlahBenihSPK = parseInt($('input[name="jumlah_benih_spk"]').val()) || 0;
            const jumlahBenihManual = parseInt($('input[name="jumlah_benih_manual"]').val()) || 0;
            const jumlahIndukManual = $('#list_induk_manual tr').length;

            // 3. Validasi untuk SPK (jika ada input)
            if (jumlahBenihSPK > 0) {
                if ($('input[name="jumlah_telur_spk"]').val() === '') {
                    alert('Jumlah telur SPK harus diisi!');
                    e.preventDefault();
                    return false;
                }
                if ($('input[name="tingkat_netas_spk"]').val() === '') {
                    alert('Tingkat penetasan SPK harus diisi!');
                    e.preventDefault();
                    return false;
                }
            }

            // 4. Validasi untuk Manual (jika ada input)
            if (jumlahIndukManual > 0) {
                if (jumlahBenihManual <= 0) {
                    alert('Jumlah benih Manual harus lebih dari 0!');
                    e.preventDefault();
                    return false;
                }
                if ($('input[name="jumlah_telur_manual"]').val() === '') {
                    alert('Jumlah telur Manual harus diisi!');
                    e.preventDefault();
                    return false;
                }
                if ($('input[name="tingkat_netas_manual"]').val() === '') {
                    alert('Tingkat penetasan Manual harus diisi!');
                    e.preventDefault();
                    return false;
                }
            }

            // 5. Validasi minimal harus ada data SPK atau Manual yang valid
            if (!(jumlahBenihSPK > 0 || (jumlahBenihManual > 0 && jumlahIndukManual > 0))) {
                alert('isi Data jumlah Harus Lebih Dari 0!');
                e.preventDefault();
                return false;
            }

            return true;
        });


        // Reset form
        $('button[type="reset"]').click(function() {
            resetWaktuManual();
            waktuFromSPK = false;
            $('#list_induk_manual').empty();
            $('#list_induk_spk').html('<tr><td colspan="3" class="text-center">Pilih waktu pemijahan terlebih dahulu</td></tr>');

            // Set tab aktif ke SPK
            $('#spk-tab').tab('show');
            $('#active_tab').val('spk');
            activeTab = 'spk';
        });
    });
</script>