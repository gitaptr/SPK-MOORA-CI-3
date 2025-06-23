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
			margin: 5px 0;
		}

		.laporan-title {
			text-align: center;
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 10px;
		}

		.info-upr {
			margin-bottom: 10px;
			font-size: 14px;
		}

		.info-upr-row {
			display: flex;
			align-items: center;
			margin-bottom: 5px;
		}

		.info-upr-label {
			font-weight: bold;
			margin-right: 5px;
			width: 150px;
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
	<div class="laporan-title">Laporan Pemijahan</div>
	<div class="info-upr">
		<div class="info-upr-row">
			<div class="info-upr-label">Nama UPR :</div>
			<div class="info-upr-value" style="text-align: left;"><?= $upr_nama; ?></div>
		</div>
		<div class="info-upr-row">
			<div class="info-upr-label">Wilayah UPR :</div>
			<div class="info-upr-value" style="text-align: left;"><?= $upr_wilayah; ?></div>
		</div>
	</div>

	<table>
		<thead>
			<tr>
				<th width="5%">No</th>
				<th>Waktu Pemijahan</th>
				<th>Jumlah Induk (betina dan jantan)</th>
				<th>Kolam</th>
				<th>Metode Pemijahan</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach ($list as $value) {
			?>
				<tr>
					<td><?= $no++; ?></td>
					<td><?= date('d-m-Y', strtotime($value->waktu_pemijahan)) ?></td>
					<td><?= $value->jumlah_indukk ?></td>
					<td><?= $value->kolam ?></td>
					<td><?= $value->metode_pemijahan ?></td>
					<td>
						<?php
						switch ($value->status) {
							case 0:
								echo '<span class="badge badge-warning">Belum Diproses</span>';
								break;
							case 1:
								echo '<span class="badge badge-success">Diproses</span>';
								break;
							case 2:
								echo '<span class="badge badge-primary">Selesai Diproses</span>';
								break;
						}
						?>
					</td>
				</tr>
			<?php
			}
			?>
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