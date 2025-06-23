<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Wilayah</h1>

   
</div>

<?= $this->session->flashdata('message'); ?>


<div class="card shadow mb-4">
	<div class="card-header py-3">
	<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data Wilayah </h6>
    <h6 class="btn btn-success" data-toggle="modal" data-target="#modal-Input">
        <i class="fa fa-plus"></i> Tambah Data </h6>
    </div>
	 </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Kode Wilayah</th>
						<th>Nama Wilayah</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no=1;
						foreach ($list as $data => $value) {
					?>
					<tr align="center">
						<td><?=$no ?></td>
						<td align="left"><?php echo $value->kode_wilayah?></td>
						<td align="left"><?php echo $value->nama_wilayah ?></td>
						<td>
                        <div class="btn-group" role="group">
							<a href="#" class="btn btn-success btn-sm" onclick="showEditModal(<?= $value->id_wilayah ?>)" title="Edit Data"><i class="fa fa-edit"></i></a>

								<a  data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?=base_url('Wilayah/destroy/'.$value->id_wilayah)?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
							</div>
						</td>
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


<div class="modal fade" id="modal-Input">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Wilayah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
			<form class="form-horizontal" method="post" action="<?php echo base_url('Wilayah/store') ?>">
    <div class="form-group ">
        <label class="font-weight-bold">Kode Wilayah</label>
        <input autocomplete="off" type="text" name="kode_wilayah" required class="form-control"/>
    </div>
    <div class="form-group ">
        <label class="font-weight-bold">Nama Wilayah</label>
        <input autocomplete="off" type="text" name="nama_wilayah" required class="form-control"/>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" class="btn btn-warning"><i class="fa fa-sync-alt"></i> Reset</button>
    </div>
</form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="modal-Edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEdit" method="post" action="<?= base_url('Wilayah/update') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Wilayah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_wilayah" id="edit_id_wilayah" />
                    <div class="form-group">
                        <label for="edit_nama_ikan">Kode Wilayah </label>
                        <input type="text" class="form-control" name="kode_wilayah" id="edit_kode_wilayah" required />
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah">Nama Wilayah</label>
                        <input type="text" class="form-control" name="nama_wilayah" id="edit_nama_wilayah" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
<script>
   function showEditModal(id) {
    $.ajax({
        url: '<?= base_url("Wilayah/show") ?>', // Endpoint untuk mengambil data
        type: 'GET',
        data: { id_wilayah: id }, // Kirim ID Wilayah sebagai parameter
        dataType: 'json',
        success: function(data) {
            // Isi data ke input form di modal
            $('#edit_id_wilayah').val(data.id_wilayah);
            $('#edit_kode_wilayah').val(data.kode_wilayah);
            $('#edit_nama_wilayah').val(data.nama_wilayah);
            
            // Tampilkan modal edit
            $('#modal-Edit').modal('show');
        },
        error: function() {
            alert('Gagal mengambil data. Silakan coba lagi.');
        }
    });
}

</script>

</body>
</html>

