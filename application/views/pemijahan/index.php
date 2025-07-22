<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-bar"></i> Data Pemijahan</h1>

	<div class="d-flex">
		<?php if ($user_level == 2 && !empty($selected_upr)): ?>
			<a href="<?= base_url('Pemijahan/cetak_laporan?upr_id=' . $selected_upr) ?>"
				class="btn btn-primary mr-2"
				target="_blank">
				<i class="fa fa-print"></i> Cetak Data
			</a>
		<?php endif; ?>
	</div>
</div>

<?php if ($user_level == 3): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Data Pemijahan diisi sebelum melakukan pemijahan.</strong>
    <ul class="mt-2 mb-0">
       <li>Tekan tombol <strong>Tambah Data</strong> dan isi data pemijahan.</li>
         <li>Pastikan data yang diisi benar dan tekan tombol <strong>Simpan</strong>.</li>
    </ul>
</div>
<?php endif; ?>

<?php if ($user_level == 2): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Melihat Rekap Data Pemijahan UPR.</strong>
    <ul class="mt-2 mb-0">
       <li><strong>Memilih UPR</strong> yang akan dilihat data pemijahannya terlebih dahulu</li>
         <li>Lalu Tekan Tombol <strong>Cetak Data</strong> jika ingin mencetak rekap data.</li>
    </ul>
</div>
<?php endif; ?>

<?php if (isset($page) && $page === 'pemijahan'): ?>
	<?= $this->session->flashdata('message'); ?>
<?php endif; ?>

<!-- Form Pilihan UPR hanya untuk user level 2 -->
<?php if ($user_level == 2): ?>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info">
				<i class="fa fa-calendar"></i> Pilih UPR
			</h6>
		</div>
		<div class="card-body">
			<form method="GET" action="<?= base_url('pemijahan') ?>">
				<div class="form-group">
					<select name="upr_id" class="form-control" onchange="this.form.submit()">
						<option value="">-- Pilih UPR --</option>
						<?php foreach ($upr_list as $upr): ?>
							<option value="<?= $upr->id_upr ?>"
								<?= ($selected_upr == $upr->id_upr) ? 'selected' : '' ?>>
								<?= $upr->nama_upr ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>
<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex justify-content-between align-items-center">
		<h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Pemijahan</h6>
		<?php if ($user_level == 3): ?>
			<a href="<?= base_url('Pemijahan/create'); ?>" class="btn btn-success">
				<i class="fa fa-plus"></i> Tambah Data
			</a>
		<?php endif; ?>
	</div>

	<div class="card-body">
		<?php if (!empty($selected_upr) || $this->session->userdata('id_user_level') == 3): ?>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="bg-info text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>Waktu Pemijahan</th>
							<th>Jumlah Calon Induk Betina Jantan</th>
							<th>Kolam Pemijahan</th>
							<th>Metode Pemijahan</th>
							<th>Status</th>
							<?php if (!$read_only): ?>
								<th>Aksi</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($list as $value) {
						?>
							<tr align="center">
								<td><?= $no ?></td>
								<td>
									<?php if (!empty($value->waktu_pemijahan) && $value->waktu_pemijahan != '0000-00-00'): ?>
										<?= date('d-m-Y', strtotime($value->waktu_pemijahan)) ?>
									<?php else: ?>
										-
									<?php endif; ?>
								</td>

								<td><?= $value->jumlah_indukk ?> ekor</td>
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
								<?php if (!$read_only): ?>
									<td>
										<div class="btn-group" role="group">
											<a href="<?= base_url('Pemijahan/edit/' . $value->id_pemijahan) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
											<a href="<?= base_url('Pemijahan/destroy/' . $value->id_pemijahan) ?>" onclick="return confirm('Apakah anda yakin untuk menghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
										</div>
									</td>
								<?php endif; ?>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<p class="text-center text-muted">Silakan pilih UPR untuk melihat data pemijahan</p>
		<?php endif; ?>
	</div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>

<script>
	document.getElementById('filter_upr').addEventListener('change', function() {
		var upr_id = this.value;
		window.location.href = "<?= base_url('Pemijahan/index?upr_id=') ?>" + upr_id;
	});
</script>
</body>

</html>