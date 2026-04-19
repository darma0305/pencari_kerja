<?php
// Fetch latest admin data for navbar
$nav_admin_id = $_SESSION['admin_id'] ?? 0;
$nav_query = mysqli_query($conn, "SELECT photo, fullname FROM admins WHERE id = $nav_admin_id");
$nav_admin = mysqli_fetch_assoc($nav_query);
$nav_photo = ($nav_admin && $nav_admin['photo'] && $nav_admin['photo'] != 'default.png') 
             ? base_url('uploads/profile/' . $nav_admin['photo']) 
             : base_url('assets/img/admin.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JobSeeker</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Outfit) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #1a1d20;
            color: white;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background: #0d6efd;
            color: white;
        }
        .main-content {
            padding: 2rem;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0 d-flex">
        <!-- Sidebar -->
        <aside class="sidebar d-none d-md-block" style="width: 260px;">
            <div class="p-4 mb-3 text-center">
                <h4 class="fw-bold mb-0 text-primary">JobSeeker<span class="text-white">Admin</span></h4>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link <?= ($active_page == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('index.php') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link <?= ($active_page == 'jobs') ? 'active' : '' ?>" href="<?= base_url('modules/jobs/index.php') ?>">
                    <i class="bi bi-briefcase"></i> Kelola Lowongan
                </a>
                <a class="nav-link <?= ($active_page == 'selection') ? 'active' : '' ?>" href="<?= base_url('modules/selection/index.php') ?>">
                    <i class="bi bi-person-check"></i> Seleksi Pelamar
                </a>
                <a class="nav-link <?= ($active_page == 'users') ? 'active' : '' ?>" href="<?= base_url('modules/users/index.php') ?>">
                    <i class="bi bi-people"></i> Kelola Pengguna
                </a>
                <div class="mt-4 px-4 text-secondary small text-uppercase fw-bold">Pengaturan</div>
                <a class="nav-link <?= ($active_page == 'profile') ? 'active' : '' ?>" href="<?= base_url('modules/profile/index.php') ?>">
                    <i class="bi bi-person-circle"></i> Edit Profil
                </a>
                <a class="nav-link text-danger mt-5" href="<?= base_url('logout.php') ?>">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg px-4">
                <div class="container-fluid">
                    <button class="btn d-md-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMobile">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <img src="<?= $nav_photo ?>" class="rounded-circle me-2" width="35" height="35" alt="" style="object-fit: cover;">
                                <span class="fw-semibold"><?= $nav_admin['fullname'] ?? 'Admin' ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="<?= base_url('modules/profile/index.php') ?>"><i class="bi bi-person me-2"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout.php') ?>"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <main class="main-content">
