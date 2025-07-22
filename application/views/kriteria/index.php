<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list-alt"></i> Data Kriteria</h1>
</div>

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-info"></i> Pilih Jenis Kelamin</h6>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<select id="jenis_kelamin" name="jenis_kelamin" class="form-control" onchange="filterJenisKelamin()">
						<option value="">Semua</option>
						<option value="Jantan" <?= $this->session->userdata('selected_jenis_kelamin') == "Jantan" ? "selected" : "" ?>>Jantan</option>
						<option value="Betina" <?= $this->session->userdata('selected_jenis_kelamin') == "Betina" ? "selected" : "" ?>>Betina</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	// Fungsi untuk mengontrol tombol "Tambah Data"
	function kontrolTombolTambah() {
		var dropdown = document.getElementById("jenis_kelamin");
		var tombolTambah = document.getElementById("tambahDataBtn");

		// Jika dropdown memilih "Jantan" atau "Betina", aktifkan tombol
		if (dropdown.value === "Jantan" || dropdown.value === "Betina") {
			tombolTambah.removeAttribute("disabled"); // Aktifkan tombol
			tombolTambah.classList.remove("disabled"); // Hapus class "disabled" (jika ada)
		} else {
			tombolTambah.setAttribute("disabled", "disabled"); // Non-aktifkan tombol
			tombolTambah.classList.add("disabled"); // Tambahkan class "disabled" (jika diperlukan)
		}
	}

	// Panggil fungsi kontrolTombolTambah saat halaman dimuat
	document.addEventListener("DOMContentLoaded", function() {
		kontrolTombolTambah(); // Set status tombol saat halaman pertama kali dimuat
	});

	// Panggil fungsi kontrolTombolTambah saat dropdown berubah
	document.getElementById("jenis_kelamin").addEventListener("change", function() {
		kontrolTombolTambah();
	});

	// Fungsi untuk filter jenis kelamin (opsional, jika sudah ada)
	function filterJenisKelamin() {
		var jenis_kelamin = document.getElementById("jenis_kelamin").value;
		window.location.href = "<?= base_url('Kriteria?jenis_kelamin=') ?>" + jenis_kelamin;
	}
</script>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
		<h6 class="m-0 font-weight-bold text-info"></i> Daftar Data Kriteria</h6>
		<div class="d-flex">
			<?php if (!$read_only): ?> <!-- Hanya tampilkan jika bukan read-only -->
				<a href="<?= base_url('Kriteria/create'); ?>" id="tambahDataBtn" class="btn btn-success" disabled>
					<i class="fa fa-plus"></i> Tambah Data
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Kode Kriteria</th>
						<th>Nama Kriteria</th>
						<th>Bobot</th>
						<th>Jenis</th>
						<?php if (!$read_only): ?> <!-- Hanya tampilkan jika bukan read-only -->
							<th>Aksi</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($list as $data => $value) {
					?>
						<tr align="center">
							<td><?= $no ?></td>
							<td><?php echo $value->kode_kriteria ?></td>
							<td><?php echo $value->keterangan ?></td>
							<td><?php echo $value->bobot ?></td>
							<td><?php echo $value->jenis ?></td>
							<?php if (!$read_only): ?> <!-- Hanya tampilkan jika bukan read-only -->
								<td>
									<div class="btn-group" role="group">
										<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="<?= base_url('Kriteria/edit/' . $value->id_kriteria) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
										<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?= base_url('Kriteria/destroy/' . $value->id_kriteria . '?jenis_kelamin=' . $this->input->get('jenis_kelamin')) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
	</div>
</div>


<?php $this->load->view('layouts/footer_admin'); ?>