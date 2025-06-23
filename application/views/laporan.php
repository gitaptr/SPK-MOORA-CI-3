<!DOCTYPE html>
<html>

<head>
    <title>Laporan Hasil Akhir</title>
    <style>
        @media print {
            th {
                background-color: rgb(255, 255, 255) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
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
            height: 60px;
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

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: rgb(255, 255, 255);
            color: black;
        }

        .laporan-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-pemijahan {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <div class="kop-container">
            <img src="<?= base_url('assets/img/lgo.png') ?>" alt="Logo">
            <div class="kop-text">
                <h1>DINAS PERIKANAN DAN PANGAN KOTA PADANG</h1>
                <h3>Jalan Muara No 51, Padang Barat, Padang</h3>
                <h3>Telp: (0751) 33288 | Email: dppkotapadang@gmail.com</h3>
            </div>
        </div>
    </div>
    <hr>
    <div class="laporan-title">Laporan Hasil Rekomendasi Induk Ikan Lele</div>

    <div class="info-pemijahan">
        <table style="border: none;">
            <tr>
                <td style="border: none; text-align: left; width: 18%;">Waktu Pemijahan</td>
                <td style="border: none; text-align: center; width: 5%;">:</td>
                <td style="border: none; text-align: left; width: 70%;">
                    <?= isset($detail_pemijahan->waktu_pemijahan) ? date('d-m-Y', strtotime($detail_pemijahan->waktu_pemijahan)) : 'Tidak tersedia'; ?>
                </td>
            </tr>
            <tr>
                <td style="border: none; text-align: left;">Kolam</td>
                <td style="border: none; text-align: center;">:</td>
                <td style="border: none; text-align: left;">
                    <?= isset($detail_pemijahan->kolam) ? $detail_pemijahan->kolam : 'Tidak tersedia'; ?>
                </td>
            </tr>
            <tr>
                <td style="border: none; text-align: left;">Metode Pemijahan</td>
                <td style="border: none; text-align: center;">:</td>
                <td style="border: none; text-align: left;">
                    <?= isset($detail_pemijahan->metode_pemijahan) ? $detail_pemijahan->metode_pemijahan : 'Tidak tersedia'; ?>
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <!-- Kriteria Jantan -->
    <?php if (!empty($kriteria_list_jantan)): ?>
        <h4 style="margin-bottom: 10px;">Informasi Kriteria Jantan</h4>
        <table>
            <thead>
                <tr>
                    <?php foreach ($kriteria_list_jantan as $kode => $kriteria): ?>
                        <th><?= htmlspecialchars($kriteria['keterangan'], ENT_QUOTES, 'UTF-8') ?> (<?= $kriteria['kode'] ?>)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($kriteria_list_jantan as $kriteria): ?>
                        <td><?= htmlspecialchars($kriteria['bobot'], ENT_QUOTES, 'UTF-8') ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Hasil Jantan -->
    <?php if (!empty($hasil_jantan)): ?>
        <h4 style="margin-bottom: 10px;">Hasil Akhir Perhitungan - Induk Jantan</h4>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <?php foreach ($kriteria_list_jantan as $kode => $kriteria): ?>
                        <th><?= $kode ?></th>
                    <?php endforeach; ?>
                    <th>Nilai Yi</th>
                    <th>Rank</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($hasil_jantan as $item): ?>
                    <tr>
                        <td align="left"><?= $item->nama ?></td>
                        <?php foreach ($kriteria_list_jantan as $kode => $kriteria): ?>
                            <?php
                            $nilai_kriteria = '-';
                            if (!empty($item->kriteria_sub_kriteria)) {
                                foreach ($item->kriteria_sub_kriteria as $sub) {
                                    if ($sub['kode_kriteria'] == $kode) {
                                        $nilai_kriteria = $sub['nilai'];
                                        break;
                                    }
                                }
                            }
                            ?>
                            <td><?= $nilai_kriteria ?></td>
                        <?php endforeach; ?>
                        <td><?= number_format($item->nilai, 4) ?></td>
                        <td><?= $rank ?></td>
                        <td><?= ($item->status_pilih == 1) ? "Dipilih" : "Tidak Dipilih" ?></td>
                    </tr>
                    <?php $rank++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Page Break untuk Betina -->
    <div class="page-break">
        <!-- Kop Surat lagi di halaman baru -->
        <div class="kop-surat">
            <div class="kop-container">
                <img src="<?= base_url('assets/img/logopadang.png') ?>" alt="Logo Lain">
                <div class="kop-text">
                    <h1>DINAS PERIKANAN DAN PANGAN KOTA PADANG</h1>
                    <h3>Jalan Muara No 51, Padang Barat, Padang</h3>
                    <h3>Telp: (0751) 33288 | Email: dppkotapadang@gmail.com</h3>
                </div>
            </div>
        </div>
        <hr>
        <div class="laporan-title">Laporan Hasil Rekomendasi Induk Ikan Lele (Lanjutan)</div>

        <!-- Informasi Pemijahan lagi -->
        <div class="info-pemijahan">
            <table style="border: none;">
                <tr>
                    <td style="border: none; text-align: left; width: 18%;">Waktu Pemijahan</td>
                    <td style="border: none; text-align: center; width: 5%;">:</td>
                    <td style="border: none; text-align: left; width: 70%;">
                        <?= isset($detail_pemijahan->waktu_pemijahan) ? date('d-m-Y', strtotime($detail_pemijahan->waktu_pemijahan)) : 'Tidak tersedia'; ?>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; text-align: left;">Kolam</td>
                    <td style="border: none; text-align: center;">:</td>
                    <td style="border: none; text-align: left;">
                        <?= isset($detail_pemijahan->kolam) ? $detail_pemijahan->kolam : 'Tidak tersedia'; ?>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; text-align: left;">Metode Pemijahan</td>
                    <td style="border: none; text-align: center;">:</td>
                    <td style="border: none; text-align: left;">
                        <?= isset($detail_pemijahan->metode_pemijahan) ? $detail_pemijahan->metode_pemijahan : 'Tidak tersedia'; ?>
                    </td>
                </tr>
            </table>
        </div>
        <hr>

        <!-- Kriteria Betina -->
        <?php if (!empty($kriteria_list_betina)): ?>
            <h4 style="margin-bottom: 10px;">Informasi Kriteria Betina</h4>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($kriteria_list_betina as $kode => $kriteria): ?>
                            <th><?= htmlspecialchars($kriteria['keterangan'], ENT_QUOTES, 'UTF-8') ?> (<?= $kriteria['kode'] ?>)</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($kriteria_list_betina as $kriteria): ?>
                            <td><?= htmlspecialchars($kriteria['bobot'], ENT_QUOTES, 'UTF-8') ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Hasil Betina -->
        <?php if (!empty($hasil_betina)): ?>
            <h4 style="margin-bottom: 10px;">Hasil Akhir Perhitungan - Induk Betina</h4>
            <table>
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <?php foreach ($kriteria_list_betina as $kode => $kriteria): ?>
                            <th><?= $kode ?></th>
                        <?php endforeach; ?>
                        <th>Nilai Yi</th>
                        <th>Rank</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; ?>
                    <?php foreach ($hasil_betina as $item): ?>
                        <tr>
                            <td align="left"><?= $item->nama ?></td>
                            <?php foreach ($kriteria_list_betina as $kode => $kriteria): ?>
                                <?php
                                $nilai_kriteria = '-';
                                if (!empty($item->kriteria_sub_kriteria)) {
                                    foreach ($item->kriteria_sub_kriteria as $sub) {
                                        if ($sub['kode_kriteria'] == $kode) {
                                            $nilai_kriteria = $sub['nilai'];
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <td><?= $nilai_kriteria ?></td>
                            <?php endforeach; ?>
                            <td><?= number_format($item->nilai, 4) ?></td>
                            <td><?= $rank ?></td>
                            <td><?= ($item->status_pilih == 1) ? "Dipilih" : "Tidak Dipilih" ?></td>
                        </tr>
                        <?php $rank++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>