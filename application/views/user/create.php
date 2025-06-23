<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>

	<a href="<?= base_url('User'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-plus"></i> Tambah Data User</h6>
	</div>

	<?php echo form_open('User/store'); ?>
	<div class="card-body">
		<div class="row">
			<div class="form-group col-md-3">
				<label for="privilege">Level User</label>
				<select class="form-control" id="privilege" name="privilege">
					<option value="">--Pilih Level--</option>
					<?php foreach ($user_level as $level) { ?>
						<option value="<?= $level->id_user_level ?>"><?= $level->user_level ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="id_wilayah">Wilayah</label>
				<select class="form-control" id="id_wilayah" name="id_wilayah" disabled>
					<option value="">--Pilih Wilayah--</option>
					<?php foreach ($wilayah as $w) { ?>
						<option value="<?= $w->id_wilayah ?>"><?= $w->kode_wilayah ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Nama Lengkap</label>
				<input autocomplete="off" type="text" name="nama" required class="form-control" />
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Username</label>
				<input autocomplete="off" type="text" name="username" required class="form-control" />
			</div>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Password</label>
				<input autocomplete="off" type="password" name="password" required class="form-control" />
			</div>


		</div>
	</div>
	<div class="card-footer text-right">
		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
		<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
	</div>
	<?php echo form_close() ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const privilege = document.getElementById("privilege");
		const wilayah = document.getElementById("id_wilayah");

		function toggleWilayah() {
			if (privilege.value == "2") { // Jika level adalah Penyuluh
				wilayah.setAttribute("required", "required");
				wilayah.disabled = false;
			} else {
				wilayah.removeAttribute("required");
				wilayah.disabled = true;
				wilayah.value = ""; // Reset pilihan wilayah
			}
		}

		// Jalankan saat halaman dimuat dan setiap ada perubahan privilege
		privilege.addEventListener("change", toggleWilayah);
		toggleWilayah(); // Jalankan saat pertama kali halaman dimuat
	});
</script>

</body>

</html>