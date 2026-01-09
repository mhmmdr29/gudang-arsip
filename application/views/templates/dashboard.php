<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Gudang Arsip</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #212529;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            background-color: white;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        .alert {
            border-radius: 8px;
        }
        .table {
            background-color: white;
        }
        .table thead th {
            background-color: #0d6efd;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white mb-4">
            <i class="fas fa-warehouse"></i> Gudang Arsip
        </h4>

        <nav class="nav flex-column">
            <a class="nav-link active" href="<?php echo base_url(); ?>dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="<?php echo base_url(); ?>archives">
                <i class="fas fa-box"></i> Arsip
            </a>
            <a class="nav-link" href="<?php echo base_url(); ?>warehouses">
                <i class="fas fa-building"></i> Gudang
            </a>
            <a class="nav-link" href="<?php echo base_url(); ?>shelves">
                <i class="fas fa-layer-group"></i> Rak
            </a>
            <a class="nav-link" href="<?php echo base_url(); ?>movements">
                <i class="fas fa-exchange-alt"></i> Pindah
            </a>
            <a class="nav-link" href="<?php echo base_url(); ?>borrowings">
                <i class="fas fa-hand-holding"></i> Pinjam
            </a>
            <hr class="text-white my-3">
            <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'KASSUBAG_KUL'): ?>
            <a class="nav-link" href="<?php echo base_url(); ?>accounts">
                <i class="fas fa-users"></i> Akun
            </a>
            <?php endif; ?>
            <a class="nav-link text-danger" href="<?php echo base_url(); ?>auth/logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i>
                        Selamat datang, <?php echo $this->session->userdata('username'); ?>!
                    </h5>
                    <small class="text-white">
                        Role: <?php echo $this->session->userdata('role'); ?>
                    </small>
                </div>
                <a href="<?php echo base_url(); ?>auth/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <?php echo $content; ?>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
