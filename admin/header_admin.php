<?php
session_start();
ob_start();
require_once '../koneksi.php';

// Ambil favicon
$sql_logo_admin = "SELECT favicon FROM profil WHERE id = 1";
$res_logo_admin = $conn->query($sql_logo_admin);
$data_logo_admin = $res_logo_admin ? $res_logo_admin->fetch_assoc() : null;
$favicon_img_admin = (!empty($data_logo_admin['favicon'])) ? '../uploads/' . $data_logo_admin['favicon'] : '../images/favicon.ico';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PAUDQU Al-Ikhlas</title>
    <link rel="icon" href="<?= $favicon_img_admin ?>">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; transition: all 0.3s ease-in-out; }
        .sidebar a { color: #d1d5db; text-decoration: none; padding: 12px 20px; display: block; border-radius: 5px; margin-bottom: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #28a745; color: white; }
        .content { padding: 20px; transition: all 0.3s ease-in-out; min-height: 100vh; }
        .top-navbar { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; }
        
        @media (max-width: 768px) {
            .sidebar { 
                position: fixed; 
                left: -250px; 
                width: 250px; 
                height: 100vh; 
                z-index: 1050; 
                overflow-y: auto; 
                padding-bottom: 20px; 
            }
            .sidebar.show { left: 0; }
            .sidebar-overlay {
                position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.5); z-index: 1040; display: none;
            }
            .sidebar-overlay.show { display: block; }
        }
        
        /* Efek Pengembang (Zoom) untuk seluruh foto saat disentuh/di-hover */
        img { transition: transform 0.4s ease-in-out; }
        img:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar p-3" id="sidebarMenu">
            <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
                <h5 class="text-white fw-bold mb-0">Menu</h5>
                <button class="btn btn-sm btn-outline-light" id="closeSidebar"><i class="fas fa-times"></i></button>
            </div>
            <h4 class="text-white text-center fw-bold mb-4 mt-2 d-none d-md-block">PAUDQU Admin</h4>
            <div class="text-center mb-4 bg-dark p-3 rounded">
                <span class="text-light d-block" style="font-size: 0.9rem;">Halo, <strong class="text-success">Admin</strong></span>
                <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-success mt-2 w-100"><i class="fas fa-external-link-alt me-1"></i> Lihat Website</a>
            </div>
            <hr class="text-secondary mt-0">
            <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="guru.php" class="<?= in_array($current_page, ['guru.php', 'tambah.php', 'edit.php']) ? 'active' : '' ?>"><i class="fas fa-chalkboard-teacher me-2"></i> Data Guru</a>
            
            <a href="kegiatan.php" class="<?= in_array($current_page, ['kegiatan.php', 'tambah_kegiatan.php', 'edit_kegiatan.php']) ? 'active' : '' ?>"><i class="fas fa-calendar-alt me-2"></i> Data Kegiatan</a>
            <a href="pengumuman.php" class="<?= in_array($current_page, ['pengumuman.php', 'tambah_pengumuman.php', 'edit_pengumuman.php']) ? 'active' : '' ?>"><i class="fas fa-bullhorn me-2"></i> Pengumuman</a>
            
            <a href="fasilitas.php" class="<?= in_array($current_page, ['fasilitas.php', 'tambah_fasilitas.php', 'edit_fasilitas.php']) ? 'active' : '' ?>"><i class="fas fa-building me-2"></i> Fasilitas</a>
            <a href="galeri.php" class="<?= in_array($current_page, ['galeri.php', 'tambah_galeri.php']) ? 'active' : '' ?>"><i class="fas fa-images me-2"></i> Galeri</a>
            <a href="profil.php" class="<?= ($current_page == 'profil.php') ? 'active' : '' ?>"><i class="fas fa-bullseye me-2"></i> Profil & Kontak</a>
            <a href="pengaturan.php" class="<?= ($current_page == 'pengaturan.php') ? 'active' : '' ?>"><i class="fas fa-image me-2"></i> Pengaturan Logo</a>
            
            <hr class="text-secondary">
            <a href="logout.php" class="text-danger mt-auto"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 content">
            <div class="d-flex justify-content-between align-items-center mb-4 d-md-none top-navbar">
                <h5 class="mb-0 fw-bold text-muted">Administrator</h5>
                <button class="btn btn-outline-secondary" id="openSidebar"><i class="fas fa-bars"></i></button>
            </div>
