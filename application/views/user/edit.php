<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>

	<a href="<?= base_url('User'); ?>" class="btn btn-secondary btn-icon-split">
		<span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data User</h6>
	</div>

	<?php echo form_open('User/update/' . $User->id_user); ?>
	<div class="card-body">
		<div class="row">
			<?php echo form_hidden('id_user', $User->id_user) ?>

			<div class="form-group col-md-3">
				<label class="font-weight-bold">Level</label>
				<select class="form-control" name="privilege" id="privilege" required>
					<?php foreach ($user_level as $k) {
						$s = '';
						if ($k->id_user_level == $User->id_user_level) {
							$s = 'selected';
						}
					?>
						<option value="<?php echo $k->id_user_level ?>" <?php echo $s ?>>
							<?php echo $k->user_level ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group col-md-3">
				<label class="font-weight-bold">Wilayah</label>
				<select class="form-control" name="id_wilayah" id="id_wilayah"
					<?php echo ($User->id_user_level == 2 || $User->id_user_level == 3) ? 'required' : ''; ?>>
					<option value="">--Pilih Kode Wilayah--</option>
					<?php foreach ($wilayah as $w) {
						$selected = $w->id_wilayah == $User->id_wilayah ? 'selected' : '';
					?>
						<option value="<?= $w->id_wilayah ?>" <?= $selected ?>>
							<?= $w->kode_wilayah ?>
						</option>
					<?php } ?>
				</select>
			</div>



			<div class="form-group col-md-6">
				<label class="font-weight-bold">Nama Lengkap</label>
				<input autocomplete="off" type="text" name="nama" value="<?php echo $User->nama ?>" required class="form-control" />
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Username</label>
				<input autocomplete="off" type="text" name="username" value="<?php echo $User->username ?>" required class="form-control" />
			</div>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Password</label>
				<input autocomplete="off" type="password" name="password" required class="form-control" />
			</div>
		</div>
	</div>
	<div class="card-footer text-right">
		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
		<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
	</div>
	<?php echo form_close() ?>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const privilege = document.getElementById("privilege");
		const wilayah = document.getElementById("id_wilayah");

		function toggleWilayah() {
			if (privilege.value == "2" || privilege.value == "3") { // Aktif untuk level 2 dan 3
				wilayah.setAttribute("required", "required");
				wilayah.disabled = false;
			} else {
				wilayah.removeAttribute("required");
				wilayah.disabled = true;
				wilayah.value = ""; // Reset pilihan wilayah
			}
		}


		privilege.addEventListener("change", toggleWilayah);
		toggleWilayah(); // Jalankan saat halaman pertama kali dimuat
	});
</script>

<?php $this->load->view('layouts/footer_admin'); ?>