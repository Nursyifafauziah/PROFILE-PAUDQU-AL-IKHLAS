<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'koneksi.php'; 
// Ambil logo dan favicon dari profil
$sql_logo = "SELECT logo, favicon FROM profil WHERE id = 1";
$res_logo = $conn->query($sql_logo);
$data_logo = $res_logo ? $res_logo->fetch_assoc() : null;
$logo_img = (!empty($data_logo['logo'])) ? 'uploads/' . $data_logo['logo'] : 'images/logo.png';
$favicon_img = (!empty($data_logo['favicon'])) ? 'uploads/' . $data_logo['favicon'] : 'images/favicon.ico';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUDQU Al-Ikhlas</title>
    <link rel="icon" href="<?= $favicon_img ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css?v=<?= time() ?>">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <img src="<?= $logo_img ?>" alt="Logo PAUDQU Al-Ikhlas" width="45" height="45" class="d-inline-block align-text-middle me-2 rounded-circle shadow-sm" style="object-fit: cover;">
            PAUDQU Al-Ikhlas
        </a>
        <!-- Desktop menu (visible on lg+) -->
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNavDesktop">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>" href="about.php">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'fasilitas.php') ? 'active' : ''; ?>" href="fasilitas.php">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'galeri.php') ? 'active' : ''; ?>" href="galeri.php">Galeri</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= (basename($_SERVER['PHP_SELF']) == 'kegiatan.php') ? 'active' : ''; ?>" href="#" id="kegiatanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kegiatan
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="kegiatanDropdown">
                        <li><a class="dropdown-item" href="kegiatan.php?type=pengumuman">Pengumuman</a></li>
                        <li><a class="dropdown-item" href="kegiatan.php?type=berita">Berita</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'guru.php') ? 'active' : ''; ?>" href="guru.php">Data Guru</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kontak.php') ? 'active' : ''; ?>" href="kontak.php">Kontak</a>
                </li>

            </ul>
        </div>

        <!-- Hamburger button (visible on mobile only) -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas mobile menu (slides from right) -->
        <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="mobileMenuLabel">
                    <img src="<?= $logo_img ?>" alt="Logo" width="35" height="35" class="rounded-circle me-2" style="object-fit: cover;">
                    PAUDQU Al-Ikhlas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>" href="about.php">
                            <i class="fas fa-info-circle me-2"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'fasilitas.php') ? 'active' : ''; ?>" href="fasilitas.php">
                            <i class="fas fa-building me-2"></i>Fasilitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'galeri.php') ? 'active' : ''; ?>" href="galeri.php">
                            <i class="fas fa-images me-2"></i>Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#mobileKegiatanSub" role="button" aria-expanded="false">
                            <i class="fas fa-calendar-alt me-2"></i>Kegiatan
                            <i class="fas fa-chevron-down float-end mt-1" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="collapse ps-4" id="mobileKegiatanSub">
                            <a class="nav-link py-2" href="kegiatan.php?type=pengumuman">
                                <i class="fas fa-bullhorn me-2"></i>Pengumuman
                            </a>
                            <a class="nav-link py-2" href="kegiatan.php?type=berita">
                                <i class="fas fa-newspaper me-2"></i>Berita
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'guru.php') ? 'active' : ''; ?>" href="guru.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kontak.php') ? 'active' : ''; ?>" href="kontak.php">
                            <i class="fas fa-envelope me-2"></i>Kontak
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
