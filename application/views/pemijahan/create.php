<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Pemijahan</h1>

	<a href="<?= base_url('Pemijahan'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?php if (isset($page) && $page === 'pemijahan'): ?>
	<?= $this->session->flashdata('message'); ?>
<?php endif; ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-plus"></i> Tambah Data Pemijahan</h6>
	</div>

	<?php echo form_open('Pemijahan/store'); ?>
	<div class="card-body">
		<div class="row">
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Waktu Pemijahan</label>
				<input autocomplete="off" type="date" name="waktu_pemijahan" id="waktu_pemijahan" required class="form-control" />
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold">Jumlah Induk (Betina dan Jantan)</label>
				<input autocomplete="off" type="number" name="jumlah_indukk" min="1" required class="form-control" />
			</div>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Kolam</label>
				<select name="kolam" class="form-control" required>
					<option value="">--Pilih Kolam--</option>
					<?php if (!empty($kolam_list)): ?>
						<?php foreach ($kolam_list as $kolam): ?>
							<option value="<?= htmlspecialchars($kolam->kode_kolam) ?>"><?= htmlspecialchars($kolam->kode_kolam) ?></option>
						<?php endforeach; ?>
					<?php else: ?>
						<option value="">Kolam tidak tersedia</option>
					<?php endif; ?>
				</select>
			</div>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Metode Pemijahan</label>
				<select name="metode_pemijahan" class="form-control" required>
					<option value="">--Pilih Metode Pemijahan--</option>
					<option value="Alami">Alami</option>
					<option value="Buatan">Buatan</option>
				</select>
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
			const today = new Date().toISOString().split('T')[0];
			document.getElementById("waktu_pemijahan").setAttribute("max", today);
		});
	</script>