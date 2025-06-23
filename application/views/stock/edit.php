<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Stok Benih Ikan</h1>

    <a href="<?= base_url('Stock'); ?>" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
        <span class="text">Kembali</span>
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-edit"></i> Edit Data Stok Benih Ikan</h6>
    </div>
    <?php echo form_open('Stock/update/' . $stock->id_stok_benih); ?>
    <div class="card-body">
        <div class="row">
            <?php echo form_hidden('id_stok_benih', $stock->id_stok_benih) ?>
            <div class="form-group col-md-6">
                <label class="font-weight-bold">Tanggal</label>
                <input autocomplete="off" type="date" name="tanggal" value="<?php echo $stock->tanggal ?>" required class="form-control" />
            </div>

            <div class="form-group col-md-6">
                <label for="jumlah">Jumlah Benih</label>
                <input type="number" name="jumlah" class="form-control" id="jumlah"
                    value="<?= $stock->jumlah ?>"
                    <?= $stock->waktu_pemijahan ? 'readonly' : '' ?>>
                <?php if ($stock->waktu_pemijahan): ?>
                    <small class="text-muted">Jumlah ini berasal dari hasil pemijahan dan tidak dapat diedit.</small>
                <?php endif; ?>
            </div>

            <div class="form-group col-md-6">
                <label class="font-weight-bold">Ukuran</label>
                <input autocomplete="off" type="text" name="ukuran" value="<?php echo $stock->ukuran ?>" required class="form-control" />
            </div>

            <div class="form-group col-md-6">
                <label class="font-weight-bold">Umur</label>
                <input autocomplete="off" type="text" name="umur" value="<?php echo $stock->umur ?>" required class="form-control" />
            </div>
            <div class="form-group col-md-6">
                <label class="font-weight-bold">Kolam</label>
                <select name="kolam" class="form-control" required>
                    <option value="">-- Pilih Kolam --</option>
                    <?php foreach ($kolam_list as $kolam): ?>
                        <option value="<?= $kolam->kode_kolam ?>" <?= ($kolam->kode_kolam == $stock->kolam) ? 'selected' : '' ?>>
                            <?= $kolam->kode_kolam ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label class="font-weight-bold">Sumber</label>
                <input autocomplete="off" type="text" name="sumber" value="<?php echo $stock->sumber ?>" required class="form-control" />
            </div>
            <div class="form-group col-md-6">
                <label class="font-weight-bold">Keterangan</label>
                <input autocomplete="off" type="text" name="keterangan" value="<?php echo $stock->keterangan ?>" required class="form-control" />
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