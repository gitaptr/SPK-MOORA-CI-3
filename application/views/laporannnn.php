<!DOCTYPE html>
<html>

<head>
    <title>Laporan Detail Historis</title>
    <style>
        @media print {
            th {
                /* background-color: #d3d3f7 !important; */
                /* Gunakan warna yang diinginkan */
                -webkit-print-color-adjust: exact;
                /* Pastikan warna dipaksa dicetak */
                print-color-adjust: exact;

            }

            .page-break {
                page-break-before: always;
                /* Memaksa halaman baru */
            }

            td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .informasi-table th {
                background-color: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }



        body {
            font-family: 'Times New Roman', Times, serif, sans-serif;
            margin: 20px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop-container {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .kop-container img {
            height: 70px;
            width: 70px;
            margin-right: 5px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            margin: 5px 0;
            font-size: 18px;
        }

        .kop-text h3 {
            margin: 3px 0;
            font-size: 14px;
        }

        .kop-text h2 {
            margin: 3px 0;
            font-size: 16px;
        }

        .laporan-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        h4 {
            margin: 0;
            font-size: 18px;
            margin-bottom: 15px;
            /* Hilangkan margin bawaan */
        }

        h5 {
            margin: 0;
            font-size: 13px;
            /* Hilangkan margin bawaan */
        }

        p {
            margin: 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: rgb(255, 255, 255);
        }

        .total-row {
            font-weight: bold;
            background-color: rgb(255, 255, 255);
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .footer p {
            margin-bottom: 50px;
        }

        .informasi-table {
            width: 100%;
            border: none;
            background: transparent;
        }

        .informasi-table th,
        .informasi-table td {
            font-weight: normal !important;
            border: none !important;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <div class="kop-container">
            <img src="<?= base_url('assets/img/logopadang.png') ?>" alt="Logo Lain">
            <div class="kop-text">
                <h1>DINAS PERIKANAN DAN PANGAN KOTA PADANG</h1>
                <h3>Jalan Muara No 51, Padang Barat, Padang</h3>
                <h3>Telpon: (0751) 33288. Email: dppkotapadang@gmail.com.</h3>
            </div>
        </div>
    </div>
    <hr>
    <div class="laporan-title">Historis Hasil Pemilihan Induk Ikan Lele</div>

    <!-- Informasi Detail Historis -->
    <?php if (!empty($detail_historis)) : ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <table class="table informasi-table">
                            <tbody>
                                <tr>
                                    <th>Waktu Pemijahan</th>
                                    <td>: <?= !empty($detail_historis->waktu_pemijahan) ? date('d-m-Y', strtotime($detail_historis->waktu_pemijahan)) : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Metode Pemijahan</th>
                                    <td>: <?= !empty($detail_historis->metode_pemijahan) ? htmlspecialchars($detail_historis->metode_pemijahan, ENT_QUOTES, 'UTF-8') : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Kolam</th>
                                    <td>: <?= !empty($detail_historis->kolam) ? htmlspecialchars($detail_historis->kolam, ENT_QUOTES, 'UTF-8') : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Simpan</th>
                                    <td>: <?= !empty($detail_historis->created_at) ? date('d-m-Y', strtotime($detail_historis->created_at)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= !empty($detail_historis->keterangan) ? htmlspecialchars($detail_historis->keterangan, ENT_QUOTES, 'UTF-8') : 'Tidak ada keterangan' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p class="text-danger">Data historis tidak ditemukan.</p>
    <?php endif; ?>

    <hr style="  margin: 10px 0;">

    <?php
    // Pisahkan hasil berdasarkan jenis kelamin
    $hasil_jantan = [];
    $hasil_betina = [];

    foreach ($hasil_moora as $keys) {
        if ($keys->jenis_kelamin == 'Jantan') {
            $hasil_jantan[] = $keys;
        } else if ($keys->jenis_kelamin == 'Betina') {
            $hasil_betina[] = $keys;
        }
    }

    // Fungsi untuk mengurutkan hasil berdasarkan nilai Yi
    function urutkan_hasil(&$data)
    {
        usort($data, function ($a, $b) {
            return $b->nilai <=> $a->nilai;
        });
    }
    // Urutkan kedua array berdasarkan nilai Yi
    urutkan_hasil($hasil_jantan);
    urutkan_hasil($hasil_betina);
    ?>

    <?php
    // Fungsi untuk mengambil daftar kriteria unik
    function get_kriteria_list($hasil, $prefix)
    {
        $kriteria_list = [];
        foreach ($hasil as $keys) {
            if (!empty($keys->kriteria_sub_kriteria)) {
                foreach ($keys->kriteria_sub_kriteria as $kriteria) {
                    if (strpos($kriteria['kode_kriteria'], $prefix) === 0 && !in_array($kriteria['kode_kriteria'], $kriteria_list)) {
                        $kriteria_list[] = $kriteria['kode_kriteria'];
                    }
                }
            }
        }
        return $kriteria_list;
    }

    // Fungsi untuk menampilkan tabel hasil
    function tampilkan_tabel_hasil($hasil, $judul, $prefix)
    {
        $kriteria_list = get_kriteria_list($hasil, $prefix);
    ?>
        <h4 style="margin-bottom: 10px;"><?= $judul ?></h4>
        <table style="margin-bottom: 20px;" border="1">
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <?php foreach ($kriteria_list as $kriteria): ?>
                        <th><?= $kriteria ?></th>
                    <?php endforeach; ?>
                    <th>Nilai Yi</th>
                    <!-- <th>Rank</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($hasil as $keys): ?>
                    <tr>
                        <td align="left"><?= $keys->nama ?></td>
                        <?php
                        foreach ($kriteria_list as $kriteria) {
                            $found = false;
                            if (!empty($keys->kriteria_sub_kriteria)) {
                                foreach ($keys->kriteria_sub_kriteria as $kriteria_sub) {
                                    if ($kriteria_sub['kode_kriteria'] == $kriteria) {
                                        echo "<td>{$kriteria_sub['nilai']}</td>";
                                        $found = true;
                                        break;
                                    }
                                }
                            }
                            if (!$found) {
                                echo "<td>-</td>";
                            }
                        }
                        ?>
                        <td><?= $keys->nilai ?></td>
                        <!-- <td><?= $rank; ?></td> -->
                    </tr>
                <?php
                    $rank++;
                endforeach ?>
            </tbody>
        </table>
    <?php
    }   ?>

    <!-- Kriteria Jantan -->
    <h4>Kriteria Induk Jantan</h4>
    <?php if (!empty($kriterias_jantan)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($kriterias_jantan as $kriteria): ?>
                        <th><?= htmlspecialchars($kriteria->keterangan, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($kriteria->jenis, ENT_QUOTES, 'UTF-8') ?>)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($kriterias_jantan as $kriteria): ?>
                        <td><?= htmlspecialchars($kriteria->bobot, ENT_QUOTES, 'UTF-8') ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Tidak ada data kriteria untuk induk jantan.</p>
    <?php endif; ?>

    <hr style="margin: 20px 0;">

    <!-- Hasil Perhitungan Jantan -->
    <?php
    tampilkan_tabel_hasil($hasil_jantan, "Hasil Akhir Perhitungan", "CJ");
    ?>

    <!-- Halaman 2: Betina -->
    <div class="page-break"></div> <!-- Memaksa halaman baru -->

    <div class="kop-surat">
        <div class="kop-container">
            <img src="<?= base_url('assets/img/logopadang.png') ?>" alt="Logo Lain">
            <div class="kop-text">
                <h1>DINAS PERIKANAN DAN PANGAN KOTA PADANG</h1>
                <h3>Jalan Muara No 51, Padang Barat, Padang</h3>
                <h3>Telpon: (0751) 33288. Email: dppkotapadang@gmail.com.</h3>
            </div>
        </div>
    </div>
    <hr>
    <div class="laporan-title">Historis Hasil Pemilihan Induk Ikan Lele</div>

    <!-- Informasi Detail Historis (Sama seperti di halaman 1) -->
    <?php if (!empty($detail_historis)) : ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table informasi-table">
                            <tbody>
                                <tr>
                                    <th>Waktu Pemijahan</th>
                                    <td>: <?= !empty($detail_historis->waktu_pemijahan) ? date('d-m-Y', strtotime($detail_historis->waktu_pemijahan)) : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Metode Pemijahan</th>
                                    <td>: <?= !empty($detail_historis->metode_pemijahan) ? htmlspecialchars($detail_historis->metode_pemijahan, ENT_QUOTES, 'UTF-8') : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Kolam</th>
                                    <td>: <?= !empty($detail_historis->kolam) ? htmlspecialchars($detail_historis->kolam, ENT_QUOTES, 'UTF-8') : 'Tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Simpan</th>
                                    <td>: <?= !empty($detail_historis->created_at) ? date('d-m-Y', strtotime($detail_historis->created_at)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= !empty($detail_historis->keterangan) ? htmlspecialchars($detail_historis->keterangan, ENT_QUOTES, 'UTF-8') : 'Tidak ada keterangan' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p class="text-danger">Data historis tidak ditemukan.</p>
    <?php endif; ?>

    <hr style="margin: 10px 0;">

    <!-- Kriteria Betina -->
    <h4>Kriteria Induk Betina</h4>
    <?php if (!empty($kriterias_betina)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($kriterias_betina as $kriteria): ?>
                        <th><?= htmlspecialchars($kriteria->keterangan, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($kriteria->jenis, ENT_QUOTES, 'UTF-8') ?>)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($kriterias_betina as $kriteria): ?>
                        <td><?= htmlspecialchars($kriteria->bobot, ENT_QUOTES, 'UTF-8') ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Tidak ada data kriteria untuk induk betina.</p>
    <?php endif; ?>

    <hr style="margin: 10px 0;">

    <!-- Hasil Perhitungan Betina -->
    <?php
    tampilkan_tabel_hasil($hasil_betina, "Hasil Akhir Perhitungan", "CB");
    ?>

    <script>
        window.print();
    </script>
</body>

</html>