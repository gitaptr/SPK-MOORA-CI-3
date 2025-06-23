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
    <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-lg font-weight-bold"
        style="height: 70px;">

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
                                    <h1 class="h4 text-gray-900 mb-4">Masuk</h1>
                                </div>
                                <?php $error = $this->session->flashdata('message');
                                if ($error) { ?>
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <?php echo $error; ?>
                                    </div>
                                <?php } ?>

                                <form class="user" action="<?php echo site_url('Login/login'); ?>" method="post">
                                    <div class="form-group">
                                        <input required autocomplete="off" type="text" class="form-control form-control-user" id="exampleInputUser" placeholder="Username" name="username" />
                                    </div>
                                    <div class="form-group">
                                        <input required autocomplete="off" type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" />
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-info btn-user btn-block">
                                        <i class="fas fa-fw fa-sign-in-alt mr-1"></i> Masuk
                                    </button>
                                    <p class="text-center mt-3">
                                        Belum memiliki akun? <a href="<?= base_url('register'); ?>">Daftar di sini</a>
                                    </p>

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