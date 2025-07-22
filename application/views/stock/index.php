<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-fw fa-cube"></i> Data Stok Benih Ikan
    </h1>
    <div class="d-flex">
        <a href="<?= base_url('Stock/create'); ?>" class="btn btn-success mr-2">
            <i class="fa fa-plus"></i> Tambah Data
        </a>


        <a href="<?= base_url('Stock/cetak_laporan?upr_id=' . $id_upr) ?>" class="btn btn-primary" target="_blank">
            <i class="fa fa-print"></i> Cetak Data
        </a>
    </div>
</div>


<?= $this->session->flashdata('message'); ?>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Pengisian dan pendataan stok benih ikan yang dimiliki.</strong>
    <ul class="mt-2 mb-0">
        <li>Jika ada benih diluar hasil pemijahan, maka harus melakukan penambahan data.</li>
        <li>Tekan tombol <strong>Tambah Data</strong> dan isi data pemijahan.</li>
        <li>Pastikan data yang diisi benar dan tekan tombol <strong>Simpan</strong>.</li>
        <li>Data stok benih dari hasil pemijahan akan otomatis tercatat dengan ukuran default "0.25 cm" dan umur "1-3 hari".</li>
    </ul>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data Stok Benih Ikan</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Ukuran</th>
                        <th>Umur</th>
                        <th>Kolam</th>
                        <th>Sumber</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($list as $data => $value) {
                    ?>
                        <tr align="center">
                            <td><?= $no ?></td>
                            <td><?php echo $value->tanggal ?></td>
                            <td><?php echo $value->jumlah ?></td>
                            <td><?php echo $value->ukuran ?> cm</td>
                            <td><?php echo $value->umur ?> hari</td>
                            <td><?php echo $value->kolam ?></td>
                            <td><?php echo $value->sumber ?></td>
                            <td><?php echo $value->keterangan ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="<?= base_url('Stock/edit/' . $value->id_stok_benih) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="<?= base_url('Stock/destroy/' . $value->id_stok_benih) ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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

<?php $this->load->view('layouts/footer_admin'); ?>