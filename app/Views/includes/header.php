<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> XYZ | <?= ($pageTitle) ? $pageTitle : '' ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/dist/css/adminlte.min.css">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <a href="../../index3.html" class="navbar-brand">
                <img src="<?php echo base_url(); ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">XY<b>Z</b></span>
            </a>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <!--Home-->
                    <li class="nav-item">
                        <a href="<?= route_to('user') ?>" class="nav-link">User</a>
                    </li>
                    <!--<li class="nav-item">
                        <a href="#" class="nav-link">Contact</a>
                    </li>-->

                </ul>

                <!-- SEARCH FORM -->
                <form class="form-inline ml-0 ml-md-3">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <!-- User Account Menu -->
                <li class="nav-item dropdown user user-menu open">
                    <!-- Menu Toggle Button -->
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <!-- The user image in the navbar-->
                        <?php $icon_url = (session('userData.icon_path') != '' && session('userData.profile_image') != '')?  base_url().'/'.session('userData.icon_path').session('userData.profile_image') : base_url().'/dist/img/user2-160x160.jpg' ?>
                        <img src="<?= $icon_url ?>" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs text-capitalize"><?= (session('userData.full_name') != '' ) ? session('userData.full_name') : session('userData.email') ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <?php $thumbnail_url = (session('userData.thumbnail_path') != '' && session('userData.profile_image') != '') ?  base_url().'/'.session('userData.thumbnail_path').session('userData.profile_image') : base_url().'/dist/img/user2-160x160.jpg' ?>
                            <img src="<?= $thumbnail_url ?>" class="img-circle" alt="User Image">
                            <span class="hidden-xs">
                                <?= session('userData.email') ?>
                                <small>Member since <?= session('userData.created_date_time') ?></small>
                            </span>
                        </li>
                        <!-- Menu Footer-->
                        <div class="user-footer">
                            <div class="col-md-6 float-left">
                                <a href="<?= route_to('profile', session('userData.user_id') ); ?>" class="btn btn-sm btn-default btn-flat">Profile</a>
                            </div>
                            <div class="col-md-6 float-right">
                                <a href="<?= base_url('auth/logout'); ?>" class="btn btn-sm btn-default btn-flat float-right">Sign out</a>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->