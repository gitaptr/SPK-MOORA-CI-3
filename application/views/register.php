<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Sistem Pendukung Keputusan Metode MOORA</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/') ?>img/favicon.ico" type="image/x-icon">
</head>

<body style="background-image: url('<?= base_url('assets/') ?>img/background2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-lg font-weight-bold" style="height: 70px;">
        <div class="container d-flex align-items-center justify-content-center">
            <a class="navbar-brand text-info d-flex align-items-center" style="font-weight: 800;" href="<?= base_url('') ?>">
                <img src="<?= base_url('assets/') ?>img/logoside1.jpg" alt="Logo" style="height: 60px; width: 90px; margin-right: 10px;" />
                SPK BUDIDAYA IKAN
                <img src="<?= base_url('assets/') ?>img/lgo.png" alt="Logo" style="height: 60px; width: 90px; margin-left: 20px;" />
            </a>
        </div>
    </nav>

    <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="col-xl-5 col-lg-5 col-md-5">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Pendaftaran Akun</h1>
                                </div>
                                <!-- Flash message -->
                                <?php if ($this->session->flashdata('message')): ?>
                                    <div class="alert alert-success">
                                        <?= $this->session->flashdata('message'); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= $this->session->flashdata('error'); ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="<?= site_url('register/save'); ?>">
                                    <div class="form-group">
                                        <label for="id_upr">Pilih Nama UPR dan Wilayah dengan benar</label>
                                        <select class="form-control form-control-user" id="id_upr" name="id_upr" required>
                                            <option value="">Pilih Nama UPR</option>
                                            <?php foreach ($upr as $row): ?>
                                                <option value="<?= $row['id_upr']; ?>"><?= $row['nama_upr']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control form-control-user" id="id_wilayah" name="id_wilayah" required>
                                            <option value="">Pilih Kode Wilayah</option>
                                            <?php foreach ($wilayah as $row): ?>
                                                <option value="<?= $row['id_wilayah']; ?>"><?= $row['kode_wilayah']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-info btn-user btn-block">Daftar</button>
                                    <div class="text-center mt-3">
                                        Sudah punya akun?
                                        <a href="<?= site_url('login') ?>"> Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
</body>

</html>