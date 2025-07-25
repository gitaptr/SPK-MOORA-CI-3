<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-user"></i> Data Profile</h1>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data Profile</h6>
	</div>

	<?php echo form_open('Profile/update/' . $profile->id_user); ?>
	<div class="card-body">
		<div class="row">
			<?php echo form_hidden('id_user', $profile->id_user) ?>

			<div class="form-group col-md-6">
				<label class="font-weight-bold">Username</label>
				<input autocomplete="off" type="text" name="username" value="<?php echo $profile->username ?>" required class="form-control" />
			</div>
			<div class="form-group col-md-6">
    <label class="font-weight-bold">Password</label>
    <div class="input-group">
        <input autocomplete="off" type="password" name="password" id="password" required class="form-control"/>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                Show
            </button>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Ganti tulisan tombol
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
</script>



			<div class="form-group col-md-6">
				<label class="font-weight-bold">Nama Lengkap</label>
				<input autocomplete="off" type="text" name="nama" value="<?php echo $profile->nama ?>" required class="form-control" />
			</div>
		</div>
	</div>
	<div class="card-footer text-right">
		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
		<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
	</div>
	<?php echo form_close() ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>