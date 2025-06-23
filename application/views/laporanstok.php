<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Pemijahan</title>
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
            height: auto;
            width: 70px;
            margin-right: 10px;
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

        hr {
            border: 1px solid black;
            margin: 5px 0;
        }

        .laporan-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 19px;
        }

        .info-upr {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-upr-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-upr-label {
            font-weight: bold;
            margin-right: 5px;
            width: 150px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <div class="kop-container">
            <img src="<?= base_url('assets/img/logopadang.png') ?>" alt="Logo">
            <div class="kop-text">
                <h1>DINAS PERIKANAN DAN PANGAN KOTA PADANG</h1>
                <h3>Jalan Muara No 51, Padang Barat, Padang</h3>
                <h3>Telpon: (0751) 33288. Email: dppkotapadang@gmail.com.</h3>
            </div>
        </div>
    </div>
    <hr>
    <div class="laporan-title">LAPORAN DATA BENIH UPR</div>

    <div class="info-upr">
        <div class="info-upr-row">
            <div class="info-upr-label">Nama UPR :</div>
            <div class="info-upr-value"><?= $upr_nama; ?></div>
        </div>
        <div class="info-upr-row">
            <div class="info-upr-label">Wilayah UPR :</div>
            <div class="info-upr-value"><?= $upr_wilayah; ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah Benih</th>
                <th>Ukuran</th>
                <th>Kolam</th>
                <th>Umur</th>
                <th>Sumber</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($list as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                    <td><?= number_format($row->jumlah) ?></td>
                    <td><?= $row->ukuran ?></td>
                    <td><?= $row->kolam ?></td>
                    <td><?= $row->umur ?></td>
                    <td><?= $row->sumber ?></td>
                    <td><?= $row->keterangan ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td colspan="2" style="text-align:center;">Total Benih</td>
                <td><?= number_format($total_jumlah) ?></td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        Padang, <?= date('d F Y') ?><br><br><br><br>
        <u></u><br>
        Petugas UPR
    </div>

    <script>
        window.print();
    </script>
</body>

</html>