<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-history"></i> Historis Hasil Akhir</h1>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Data hasil akhir yang telah dilakukan dan tersimpan</strong>
    <ul class="mt-2 mb-0">
       <li>Tekan tombol <strong>Detail</strong> pada kolom aksi untuk melihat detail dari hasil akhir.</li>
    </ul>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"></> Data Historis Hasil Akhir Perhitungan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Simpan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($historis as $his) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= isset($his->created_at) ? date('d-m-Y', strtotime($his->created_at)) : '-'; ?></td>
                            <td><?= !empty($his->keterangan) ? $his->keterangan : 'Tidak ada keterangan'; ?></td>
                            <td>
                                <a href="<?= site_url('Perhitungan/detail_historis/' . $his->id_pemijahan); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i> 
                                </a>
                                <a href="<?= site_url('Perhitungan/hapus_historis/' . $his->id_pemijahan); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
                                    <i class="fas fa-trash"></i> 
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($historis)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data historis tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
