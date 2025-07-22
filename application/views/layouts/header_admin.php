 <?php
  if ($this->session->status !== ('Logged')) {
    redirect('login');
  }
  ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title>Sistem Pendukung Keputusan Metode MOORA</title>

   <!-- Custom fonts for this template-->
   <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet">

   <link href="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
   <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/logo.ico" type="image/x-icon">
   <link rel="icon" href="<?= base_url('assets/') ?>img/logo.ico" type="image/x-icon">

 </head>

 <body id="page-top">

   <!-- Page Wrapper -->
   <div id="wrapper">

     <!-- Sidebar -->
     <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

       <!-- Sidebar - Brand -->
       <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="<?= base_url('Login/home'); ?>">
         <div class="sidebar-brand-icon pt-3">
           <img src="<?= base_url('assets/') ?>img/lgo.png" alt="Logo" style="height: 50px; width: 70px;" />
         </div>
         <div class="sidebar-brand-text pb-2 mt-2">SPK BUDIDAYA IKAN</div>
       </a>


       <!-- Divider -->
       <hr class="sidebar-divider my-2">

       <!-- Nav Item - Dashboard -->
       <li class="nav-item <?php if ($page == 'Dashboard') {
                              echo 'active';
                            } ?>">
         <a class="nav-link" href="<?= base_url('Login/home'); ?>">
           <i class="fas fa-fw fa-home"></i>
           <span>Dashboard</span></a>
       </li>


       <!-- Divider -->
       <hr class="sidebar-divider">

       <!-- Heading -->

       <?php if ($this->session->userdata('id_user_level') == '1'): ?>
         <li class="nav-item <?php if ($page == 'Wilayah') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Wilayah'); ?>">
             <i class="fas fa-fw fa-cube"></i>
             <span>Data Wilayah</span></a>
         </li>
         <li class="nav-item <?php if ($page == 'Upr') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Upr'); ?>">
             <i class="fas fa-fw fa-cube"></i>
             <span>Data UPR</span></a>
         </li>
       <?php endif; ?>

       <?php if ($this->session->userdata('id_user_level') == '2'): ?>
         <li class="nav-item <?php if ($page == 'Master Data') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" ?>
             <span>Master Data</span></a>
         </li>
         <li class="nav-item <?php if ($page == 'Kriteria') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Kriteria'); ?>">
             <i class="fas fa-list-alt"></i>
             <span>Data Kriteria</span></a>
         </li>

         <li class="nav-item <?php if ($page == 'Sub Kriteria') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Sub_Kriteria'); ?>">
             <i class="fas fa-th-list"></i>
             <span>Data Sub Kriteria</span></a>
         </li>

         <li class="nav-item <?php if ($page == 'Master Data') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" ?>
             <span>Rekap Data</span></a>
         </li>
         <li class="nav-item <?php if ($page == 'Data UPR') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Data_upr/index'); ?>">
             <i class="fas fa-list"></i>
             <span>Data UPR</span>
           </a>
         </li>
         <li class="nav-item <?php if ($page == 'Pemijahan') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Pemijahan'); ?>">
             <i class="fas fa-chart-bar"></i>
             <span>Data Pemijahan</span></a>
         </li>
         <!-- <li class="nav-item <?php if ($page == 'Hasil') {
                                    echo 'active';
                                  } ?>">
           <a class="nav-link" href="<?= base_url('Perhitungan/hasil'); ?>">
             <i class="fas fa-fw fa-chart-area"></i>
             <span>Data Hasil Akhir</span></a>
         </li> -->

         <li class="nav-item <?php if ($page == 'Hasilpmj') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Hasilpmj'); ?>">
             <i class="fas fa-chart-line"></i>
             <span>Data Hasil Pemijahan</span></a>
         </li>


       <?php endif; ?>
    
       <?php if ($this->session->userdata('id_user_level') == '3'): ?>
         <!-- Sidebar Start -->
             
       <li class="nav-item <?php if ($page == 'Induk') {
                              echo 'active';
                            } ?>">
         <a class="nav-link" href="<?= base_url('Induk'); ?>">
           <i class="fas fa-fish"></i>
           <span>Induk Ikan</span></a>
       </li>
       
         <li class="nav-item <?php if ($page == 'Kolam') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Kolam'); ?>">
             <i class="fas fa-water"></i>
             <span>Kolam</span></a>
         </li>

         <li class="nav-item <?php if ($page == 'Pemijahan') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Pemijahan'); ?>">
             <i class="fas fa-fw fa-briefcase"></i>
             <span>Pemijahan</span></a>
         </li>

         <!-- SPK Dropdown -->
         <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSPK" aria-expanded="true" aria-controls="collapseSPK">
             <i class="fas fa-fw fa-cogs"></i>
             <span>SPK</span>
           </a>
           <div id="collapseSPK" class="collapse" aria-labelledby="headingSPK" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded ">
               <a class="collapse-item <?php if ($page == 'Kriteria') echo 'active'; ?>" href="<?= base_url('Kriteria'); ?>">
                 <i class="fas fa-list-alt"></i> Data Kriteria
               </a>
               <a class="collapse-item <?php if ($page == 'Sub_Kriteria') echo 'active'; ?>" href="<?= base_url('Sub_Kriteria'); ?>">
                 <i class="fas fa-th-list"></i> Data Sub Kriteria
               </a>

               <a class="collapse-item <?php if ($page == 'Alternatif') echo 'active'; ?>" href="<?= base_url('Alternatif'); ?>">
                 <i class="fas fa-random"></i> Data Alternatif
               </a>
               <a class="collapse-item <?php if ($page == 'Penilaian') echo 'active'; ?>" href="<?= base_url('Penilaian'); ?>">
                 <i class="fas fa-check-circle"></i> Data Penilaian
               </a>
               <a class="collapse-item <?php if ($page == 'Perhitungan') echo 'active'; ?>" href="<?= base_url('Perhitungan'); ?>">
                 <i class="fas fa-calculator"></i> Data Perhitungan
               </a>
               <a class="collapse-item <?php if ($page == 'Hasil') echo 'active'; ?>" href="<?= base_url('Perhitungan/hasil'); ?>">
                 <i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir
               </a>
               <a class="collapse-item <?php if ($page == 'Data Historis') echo 'active'; ?>" href="<?= base_url('Perhitungan/data_historis'); ?>">
                 <i class="fas fa-history"></i> Historis Hasil
               </a>

             </div>
           </div>
         </li>

         <li class="nav-item <?php if ($page == 'Hasilpmj') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Hasilpmj'); ?>">
             <i class="fas fa-chart-line"></i>
             <span>Hasil Pemijahan</span></a>
         </li>

         <li class="nav-item <?php if ($page == 'Stock') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('Stock'); ?>">
             <i class="fas fa-fw fa-cubes"></i>
             <span>Stock Benih</span></a>
         </li>

       <?php endif; ?>


       <!-- Divider -->
       <hr class="sidebar-divider">



       <?php if ($this->session->userdata('id_user_level') == '1'): ?>
         <li class="nav-item <?php if ($page == 'User') {
                                echo 'active';
                              } ?>">
           <a class="nav-link" href="<?= base_url('User'); ?>">
             <i class="fas fa-fw fa-users-cog"></i>
             <span>Data User</span></a>
         </li>
       <?php endif; ?>

       <li class="nav-item <?php if ($page == 'Profile') {
                              echo 'active';
                            } ?>">
         <a class="nav-link" href="<?= base_url('Profile'); ?>">
           <i class="fas fa-fw fa-user"></i>
           <span> Profile</span></a>
       </li>

       <!-- Divider -->
       <hr class="sidebar-divider d-none d-md-block">

       <!-- Sidebar Toggler (Sidebar) -->
       <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
       </div>

     </ul>
     <!-- End of Sidebar -->

     <!-- Content Wrapper -->
     <div id="content-wrapper" class="d-flex flex-column">

       <!-- Main Content -->
       <div id="content">

         <!-- Topbar -->
         <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

           <!-- Sidebar Toggle (Topbar) -->
           <button id="sidebarToggleTop" class="btn text-info d-md-none rounded-circle mr-3">
             <i class="fa fa-bars"></i>
           </button>

           <!-- Topbar Navbar -->
           <ul class="navbar-nav ml-auto">

             <!-- Nav Item - User Information -->
             <li class="nav-item dropdown no-arrow">
               <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <span class="text-uppercase mr-2 d-none d-lg-inline text-gray-600 small">
                   <?= $this->session->username; ?>
                 </span>
                 <img src="<?= base_url('assets/') ?>img/user.png" class="img-profile rounded-circle">
               </a>
               <!-- Dropdown - User Information -->
               <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                 <a class="dropdown-item" href="<?= base_url('Profile'); ?>">
                   <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                   Profile
                 </a>
                 <div class="dropdown-divider"></div>
                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                   <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                   Logout
                 </a>
               </div>
             </li>

           </ul>

         </nav>
         <!-- End of Topbar -->

         <div class="container-fluid">