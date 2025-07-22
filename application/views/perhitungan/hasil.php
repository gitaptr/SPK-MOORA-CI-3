<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir</h1>

    <div class="d-flex">
        <a href="<?= base_url('Perhitungan/cetak_laporan/' . $id_pemijahan); ?>" class="btn btn-primary mr-2" target="_blank">
            <i class="fa fa-print"></i> Cetak Data
        </a>

        <button id="btn-simpan-hasil" class="btn btn-success"
            data-id-pemijahan="<?= $id_pemijahan; ?>"
            <?= ($is_historis_exist || !$id_pemijahan) ? 'disabled' : '' ?>>
            Simpan Ke Historis
        </button>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Melihat hasil akhir perhitungan dan Mengkonfrimasi induk ikan yang akan dipijahkan</strong>
    <ul class="mt-2 mb-0">
        <li>Memilih <strong>waktu pemijahan</strong> dan <strong>Jenis Kelamin</strong> terlebih dahulu.</li>
        <li>Lanjut dengan menekan tombol <strong>Lihat</strong>.</li>
         <li>Setelah itu, mengkonfimasi induk dengan <strong>mencontreng kotak yang ada pada kolom aksi.</strong></li>
        <li>Lakukan juga pada jenis kelamin selanjutnya.</li>
         <li>Jika sudah selesai dan pasti pada kedua jenis kelamin, simpan data dengan menekan tombol <strong>Simpan Ke Historis</strong></li>
    </ul>
</div>

<!-- Form Pilihan Waktu Pemijahan dan Jenis Kelamin -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-calendar"></i> Pilih Waktu Pemijahan dan Jenis Kelamin</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('Perhitungan/hasil') ?>" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <select name="id_pemijahan" id="id_pemijahan" class="form-control" required>
                        <option value="">-- Pilih Waktu Pemijahan --</option>
                        <?php foreach ($waktu_pemijahan as $waktu): ?>
                            <option value="<?= $waktu->id_pemijahan ?>" <?= ($id_pemijahan == $waktu->id_pemijahan) ? 'selected' : '' ?>>
                                <?= date('d-m-Y', strtotime($waktu->waktu_pemijahan)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Jantan" <?= ($jenis_kelamin == 'Jantan') ? 'selected' : '' ?>>Jantan</option>
                        <option value="Betina" <?= ($jenis_kelamin == 'Betina') ? 'selected' : '' ?>>Betina</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Lihat</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="font-weight-bold text-info">Detail Waktu Pemijahan</h>
    </div>
    <div class="card-body">
        <?php if (!empty($detail_pemijahan)): ?>
            <div class="table-responsive">
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
                            <th width="30%">Waktu Pemijahan</th>
                            <td><?= date('d-m-Y', strtotime($detail_pemijahan->waktu_pemijahan)) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center ">Data pemijahan tidak tersedia.</p>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info"><i></i> Informasi Kriteria</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($kriterias)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-info text-white">
                                <tr align="center">
                                    <?php foreach ($kriterias as $kriteria): ?>
                                        <th><?= htmlspecialchars($kriteria->keterangan, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($kriteria->jenis, ENT_QUOTES, 'UTF-8') ?>)</th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr align="center">
                                    <?php foreach ($kriterias as $kriteria): ?>
                                        <td><?= htmlspecialchars($kriteria->bobot, ENT_QUOTES, 'UTF-8') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">Tidak ada data kriteria untuk jenis kelamin ini.</p>
                <?php endif; ?>
            </div>
        </div>



        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php if ($id_pemijahan && $jenis_kelamin): ?>
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-info text-white">
                                <tr align="center">
                                    <th>Alternatif</th>
                                    <th>Jenis Kelamin</th>
                                    <?php foreach ($kriterias as $kriteria): ?>
                                        <th><?= $kriteria->kode_kriteria ?></th>
                                    <?php endforeach ?>
                                    <th>Nilai Yi</th>
                                    <th>Rank</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hasil_moora)): ?>
                                    <?php
                                    usort($hasil_moora, function ($a, $b) {
                                        return $b->nilai <=> $a->nilai;
                                    });

                                    $rank = 1;
                                    ?>
                                    <?php foreach ($hasil_moora as $keys): ?>
                                        <tr align="center">
                                            <td align="center"><?= $keys->nama ?></td>
                                            <td><?= $keys->jenis_kelamin ?></td>
                                            <?php
                                            // Tampilkan nilai sub-kriteria sesuai urutan kolom
                                            foreach ($kriterias as $kriteria) {
                                                echo "<td>{$keys->nilai_kriteria[$kriteria->id_kriteria]}</td>";
                                            }
                                            ?>
                                            <td><?= $keys->nilai ?></td>
                                            <td><?= $rank; ?></td>
                                            <td>
                                                <input type="checkbox" class="pilih-induk"
                                                    name="pilih_induk[]"
                                                    value="<?= $keys->id_alternatif ?>"
                                                    data-id="<?= $keys->id_hasil_moora ?>"
                                                    <?= ($keys->status_pilih == 1) ? "checked" : "" ?>>
                                                <span class="status-text"><?= ($keys->status_pilih == 1) ? "Dipilih" : "" ?></span>
                                            </td>

                                        </tr>
                                        <?php $rank++; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" align="center">Tidak ada data untuk waktu pemijahan dan jenis kelamin ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">Silakan pilih waktu pemijahan dan jenis kelamin untuk melihat hasil perhitungan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php $this->load->view('layouts/footer_admin'); ?>

        <script>
            $(document).ready(function() {
                $(".pilih-induk").change(function() {
                    var id_hasil = $(this).data("id");
                    var isChecked = $(this).is(":checked") ? 1 : 0;
                    var statusText = $(this).siblings(".status-text");

                    $.ajax({
                        url: "<?= base_url('Perhitungan/update_pilihan') ?>",
                        type: "POST",
                        data: {
                            id_hasil: id_hasil,
                            status_pilih: isChecked
                        },
                        success: function(response) {
                            if (isChecked) {
                                statusText.text("Dipilih").css("color", "green");
                            } else {
                                statusText.text("").css("color", "");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Terjadi kesalahan:", error);
                        }
                    });
                });
            });
        </script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <script>
            $(document).ready(function() {
                $("#btn-simpan-hasil").click(function() {
                    var id_pemijahan = $(this).data("id-pemijahan");

                    if (!id_pemijahan) {
                        alert("Silakan pilih waktu pemijahan terlebih dahulu.");
                        return;
                    }

                    $.ajax({
                        url: "<?= base_url('Perhitungan/simpan_historis') ?>",
                        type: "POST",
                        data: {
                            id_pemijahan: id_pemijahan
                        },
                        dataType: "json",
                        beforeSend: function() {
                            $("#btn-simpan-hasil").prop("disabled", true).text("Menyimpan...");
                        },
                        success: function(response) {
                            console.log("Respons dari server:", response);

                            Swal.fire({
                                icon: response.status === "success" ? "success" : "error",
                                title: response.status === "success" ? "Berhasil" : "Gagal",
                                text: response.message
                            }).then(() => {
                                if (response.status === "success") {
                                    window.location.reload(); // Cukup ini saja
                                } else {
                                    $("#btn-simpan-hasil").prop("disabled", false).text("Simpan Ke Historis");
                                }
                            });
                        },

                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Silakan coba lagi.'
                            });
                            $("#btn-simpan-hasil").prop("disabled", false).text("Simpan Ke Historis");
                        }

                    });
                });
            });
        </script>

        </body>

        </html>