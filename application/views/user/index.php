<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>
	<a href="<?= base_url('User/create'); ?>" class="btn btn-info"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data User</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama</th>
						<th>Username</th>
						<th>Level</th>
						<th>Wilayah</th>
						<?php if ($this->session->userdata('id_user_level') == 1) { ?>
							<th>Status</th>
						<?php } ?>
						<th width="15%">Aksi</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$no = 1;
					if (!empty($list) && is_array($list)) {
						foreach ($list as $value) { ?>
							<tr align="center">
								<td><?= $no ?></td>
								<td><?= $value->nama ?></td>
								<td><?= $value->username ?></td>
								<td>
									<?php
									foreach ($user_level as $k) {
										if ($k->id_user_level == $value->id_user_level) {
											echo $k->user_level;
										}
									}
									?>
								</td>
								<td><?= isset($value->kode_wilayah) ? $value->kode_wilayah : '-' ?></td>

								<?php if ($this->session->userdata('id_user_level') == 1) { ?>
									<td>
										<?php if ($value->id_user_level == 3) { ?>
											<?php
											$status = isset($value->status) ? $value->status : 'Pending';
											$badge_class = 'badge-warning';
											if ($status == 'Active') {
												$badge_class = 'badge-success';
											} elseif ($status == 'Rejected') {
												$badge_class = 'badge-danger';
											} elseif ($status == 'Inactive') {
												$badge_class = 'badge-secondary';
											}
											?>
											<span class="badge <?= $badge_class ?>"><?= $status ?></span>

										<?php } elseif ($value->id_user_level == 2) { ?>
											<?php
											if ($value->status == 'Active') {
												echo '<span class="badge badge-success">Active</span>';
											} elseif ($value->status == 'Inactive') {
												echo '<span class="badge badge-secondary">Inactive</span>';
											} else {
												echo '-';
											}
											?>
										<?php } else { ?>
											-
										<?php } ?>
									</td>
								<?php } ?>


								<td>
									<div class="btn-group" role="group">
										<a href="<?= base_url('User/show/' . $value->id_user) ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
										<a href="<?= base_url('User/edit/' . $value->id_user) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>

										<?php if ($value->id_user_level == 3) { ?>
											<?php if ($value->status == 'Pending') { ?>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Active') ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui pengguna ini?')">Acc</a>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Rejected') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pengguna ini?')">Reject</a>
											<?php } elseif ($value->status == 'Active') { ?>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Inactive') ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Nonaktifkan pengguna ini?')">Deactivate</a>
											<?php } elseif ($value->status == 'Inactive') { ?>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Active') ?>" class="btn btn-success btn-sm" onclick="return confirm('Aktifkan kembali pengguna ini?')">Activate</a>
											<?php } ?>
										<?php } elseif ($value->id_user_level == 2) { ?>
											<?php if ($value->status == 'Active') { ?>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Inactive') ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Nonaktifkan penyuluh ini?')">Deactivate</a>
											<?php } elseif ($value->status == 'Inactive') { ?>
												<a href="<?= base_url('User/update_status/' . $value->id_user . '/Active') ?>" class="btn btn-success btn-sm" onclick="return confirm('Aktifkan kembali penyuluh ini?')">Activate</a>
											<?php } ?>
										<?php } ?>
									</div>
								</td>


							</tr>
						<?php $no++;
						}
					} else { ?>
						<tr>
							<td colspan="7" class="text-center">Data tidak ditemukan</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>