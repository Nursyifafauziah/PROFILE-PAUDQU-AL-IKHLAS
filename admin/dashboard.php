<?php
include 'header_admin.php';

// Menghitung jumlah guru
$sql_guru = "SELECT COUNT(*) as total FROM guru";
$result_guru = $conn->query($sql_guru);
$total_guru = $result_guru ? $result_guru->fetch_assoc()['total'] : 0;

// Menghitung jumlah berita
$sql_berita = "SELECT COUNT(*) as total FROM kegiatan WHERE tipe='berita'";
$result_berita = $conn->query($sql_berita);
$total_berita = $result_berita ? $result_berita->fetch_assoc()['total'] : 0;

// Menghitung jumlah pengumuman
$sql_pengumuman = "SELECT COUNT(*) as total FROM kegiatan WHERE tipe='pengumuman'";
$result_pengumuman = $conn->query($sql_pengumuman);
$total_pengumuman = $result_pengumuman ? $result_pengumuman->fetch_assoc()['total'] : 0;

// Ambil foto dashboard
$sql_profil = "SELECT foto_dashboard FROM profil WHERE id = 1";
$result_profil = $conn->query($sql_profil);
$data_profil = $result_profil ? $result_profil->fetch_assoc() : null;
$dashboard_img = (!empty($data_profil['foto_dashboard'])) ? '../uploads/' . $data_profil['foto_dashboard'] : '../images/hero.png';
?>

<h3 class="fw-bold mb-4">Dashboard</h3>

<div class="row">
    <!-- Card Total Guru -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold opacity-75">Total Guru</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_guru ?></h2>
                </div>
                <div>
                    <i class="fas fa-chalkboard-teacher fa-4x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-3">
                <a href="guru.php" class="text-white text-decoration-none small">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Card Berita -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold opacity-75">Total Berita</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_berita ?></h2>
                </div>
                <div>
                    <i class="fas fa-newspaper fa-4x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-3">
                <a href="kegiatan.php" class="text-white text-decoration-none small">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Card Pengumuman -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase fw-bold opacity-75">Pengumuman</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_pengumuman ?></h2>
                </div>
                <div>
                    <i class="fas fa-bullhorn fa-4x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-3">
                <a href="pengumuman.php" class="text-white text-decoration-none small">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body p-5 text-center">
        <img src="<?= htmlspecialchars($dashboard_img) ?>" alt="Welcome" class="img-fluid rounded-4 mb-4" style="max-height: 350px; width: 100%; object-fit: cover;">
        <h4 class="fw-bold text-success">Selamat Datang di Panel Admin PAUDQU Al-Ikhlas</h4>
        <p class="text-muted">Gunakan menu di sebelah kiri untuk mengelola konten website seperti Data Guru, Kegiatan, dan lainnya.</p>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
