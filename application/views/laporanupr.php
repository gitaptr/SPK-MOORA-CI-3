<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data UPR</title>
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
            height: 70px;
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
            margin: 10px 0;
        }

        .laporan-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
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
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
            background-color: rgb(252, 253, 253);
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .footer p {
            margin-bottom: 50px;
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

    <div class="laporan-title">Laporan Data UPR Wilayah <?= ($wilayah->nama_wilayah) ?></div>


    <table>
        <thead>
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
            <?php $no = 1;
            foreach ($list_upr as $upr): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($upr->nama_upr) ?></td>
                    <td><?= $upr->jumlah_kolam ?></td>
                    <td><?= $upr->jumlah_induk_betina ?></td>
                    <td><?= $upr->jumlah_induk_jantan ?></td>
                    <td><?= number_format($upr->jumlah_benih) ?></td>
                </tr>
            <?php endforeach; ?>

            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td><?= $totals['kolam'] ?></td>
                <td><?= $totals['betina'] ?></td>
                <td><?= $totals['jantan'] ?></td>
                <td><?= number_format($totals['benih']) ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        Padang, <?= date('d F Y') ?><br><br><br><br>
        <u></u><br><br>
        Penyuluh Perikanan
    </div>

    <script>
        window.print();
    </script>
</body>

</html>