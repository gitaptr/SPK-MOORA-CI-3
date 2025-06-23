<!DOCTYPE html>
<html>

<head>
	<title>Laporan Hasil Pemijahan</title>
	<style>
		@media print {

			th,
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

		h3 {
			margin: 0;
			font-size: 14px;
			margin-top: 15px;
			margin-bottom: 10px;
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

		.info-upr {
			width: 100%;
			max-width: 800px;
			margin: 20px;
			padding: 10px;
			border-bottom: 1px solid #ddd;
		}

		.info-upr-row {
			display: flex;
			justify-content: flex-start;
			padding: 5px 0;
		}

		.info-upr-label {
			width: 200px;
			/* Sesuaikan dengan kebutuhan */

		}

		.table-no-border {
			border: none;
			width: 100%;
			margin-top: 10px;
			/* Atur margin atas agar tidak terlalu mepet */
		}

		.table-no-border td {
			border: none;
			text-align: left;
			padding: 5px;
		}

		.table-no-border td:first-child {
			width: 100px;
			/* Atur lebar kolom pertama agar teks tidak terlalu jauh */
			font-weight: bold;
			/* Buat teks "Keterangan:" lebih tegas */
		}

		.page-break {
			page-break-before: always;
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
	<div class="laporan-title">Laporan Hasil Pemijahan</div>

	<div class="info-upr">
		<div class="info-upr-row">
			<div class="info-upr-label">Nama UPR</div>
			<div>: <?= $upr_nama; ?></div>
		</div>
		<div class="info-upr-row">
			<div class="info-upr-label">Wilayah UPR</div>
			<div>: <?= $upr_wilayah; ?></div>
		</div>

		<?php if (!empty($metadata)): ?>
			<div class="info-upr-row">
				<div class="info-upr-label">Waktu Pemijahan</div>
				<div>: <?= date('d-m-Y', strtotime($metadata->waktu_pemijahan)); ?></div>
			</div>
			<div class="info-upr-row">
				<div class="info-upr-label">Metode Pemijahan</div>
				<div>: <?= $metadata->metode_pemijahan; ?></div>
			</div>
			<div class="info-upr-row">
				<div class="info-upr-label">Kolam</div>
				<div>: <?= $metadata->kolam; ?></div>
			</div>
			<div class="info-upr-row">
				<div class="info-upr-label">Tanggal Simpan</div>
				<div>: <?= date('d-m-Y', strtotime($metadata->created_at)); ?></div>
			</div>
		<?php endif; ?>
	</div>

	<table>
		<thead>
			<tr>
				<th>Nama Induk</th>
				<th>Jenis Kelamin</th>
				<th>Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($induk_spk as $induk): ?>
				<tr>
					<td><?= $induk->nama; ?></td>
					<td><?= $induk->jenis_kelamin; ?></td>
					<td><?= $induk->nilai; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<h3>Hasil Pemijahan SPK</h3>
	<table>
		<thead>
			<tr>
				<th>Jumlah Telur</th>
				<th>Tingkat Netas (%)</th>
				<th>Jumlah Benih</th>
			</tr>
		</thead>
		<tbody>
			<tr align="center">
				<td><?= $spk->jumlah_telur ?? '-'; ?> telur</td>
				<td><?= $spk->tingkat_netas ?? '-'; ?>%</td>
				<td><?= $spk->jumlah_benih ?? '-'; ?> ekor</td>
			</tr>
		</tbody>

	</table>

	<!-- Keterangan SPK dalam tabel tanpa border -->
	<table class="table-no-border">
		<tr>
			<td><strong>Keterangan:</strong></td>
			<td><?= !empty($induk_spk[0]->ket_hasilpmj) ? $induk_spk[0]->ket_hasilpmj : '-'; ?></td>
		</tr>
	</table>
	<div style="margin-top: 25px; text-align: right;">
		Padang, <?= date('d F Y') ?><br><br><br><br>
		<u></u><br><br>
		Penyuluh Perikanan
	</div>

	<!-- Pemisah Halaman -->
	<div class="page-break">

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
			<div class="laporan-title">Laporan Hasil Pemijahan</div>

			<div class="info-upr">
				<div class="info-upr-row">
					<div class="info-upr-label">Nama UPR</div>
					<div>: <?= $upr_nama; ?></div>
				</div>
				<div class="info-upr-row">
					<div class="info-upr-label">Wilayah UPR</div>
					<div>: <?= $upr_wilayah; ?></div>
				</div>

				<?php if (!empty($metadata)): ?>
					<div class="info-upr-row">
						<div class="info-upr-label">Waktu Pemijahan</div>
						<div>: <?= date('d-m-Y', strtotime($metadata->waktu_pemijahan)); ?></div>
					</div>
					<div class="info-upr-row">
						<div class="info-upr-label">Metode Pemijahan</div>
						<div>: <?= $metadata->metode_pemijahan; ?></div>
					</div>
					<div class="info-upr-row">
						<div class="info-upr-label">Kolam</div>
						<div>: <?= $metadata->kolam; ?></div>
					</div>
					<div class="info-upr-row">
						<div class="info-upr-label">Tanggal Simpan</div>
						<div>: <?= date('d-m-Y', strtotime($metadata->created_at)); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<table>
				<thead>
					<tr>
						<th>Nama Induk</th>
						<th>Jenis Kelamin</th>
						<th>Kolam Induk</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($induk_manual as $item): ?>
						<tr>
							<td><?= $item->induk; ?></td>
							<td><?= $item->jenis_kelamin; ?></td>
							<td><?= $item->kolam_induk; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h3>Hasil Pemijahan Manual</h3>
			<table>
				<thead>
					<tr>
						<th>Jumlah Telur</th>
						<th>Tingkat Netas (%)</th>
						<th>Jumlah Benih</th>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<td><?= $manual->jumlah_telur ?? '-'; ?> telur</td>
						<td><?= $manual->tingkat_netas ?? '-'; ?>%</td>
						<td><?= $manual->jumlah_benih ?? '-'; ?> ekor</td>
					</tr>
				</tbody>
			</table>

			<!-- Keterangan Manual dalam tabel tanpa border -->
			<table class="table-no-border">
				<tr>
					<td><strong>Keterangan:</strong></td>
					<td><?= !empty($induk_manual[0]->ket) ? $induk_manual[0]->ket : '-'; ?></td>
				</tr>
			</table>
	</div>
	<div style="margin-top: 25px; text-align: right;">
		Padang, <?= date('d F Y') ?><br><br><br><br>
		<u></u><br><br>
		Penyuluh Perikanan
	</div>
	<script>
		window.print();
	</script>
</body>

</html>