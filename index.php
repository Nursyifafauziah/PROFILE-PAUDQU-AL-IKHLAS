<?php 
include 'header.php'; 

// Ambil data dari profil
$sql_profil = "SELECT foto_dashboard, hero_title, hero_subtitle FROM profil WHERE id = 1";
$result_profil = $conn->query($sql_profil);
$data_profil = $result_profil ? $result_profil->fetch_assoc() : null;

$hero_img = (!empty($data_profil['foto_dashboard'])) ? 'uploads/' . $data_profil['foto_dashboard'] : 'images/hero.png';
$hero_title = (!empty($data_profil['hero_title'])) ? $data_profil['hero_title'] : 'Dengan Ilmu, Hidup Jadi Bermutu';
$hero_subtitle = (!empty($data_profil['hero_subtitle'])) ? $data_profil['hero_subtitle'] : 'Selamat datang di PAUDQU Al-Ikhlas. Kami berkomitmen untuk memberikan pendidikan usia dini yang berkualitas, memadukan nilai-nilai islami dan kurikulum modern untuk mencetak generasi penerus yang cerdas, kreatif, dan berakhlak mulia.';
?>
<div class="page-header fade-in">
    <div class="container">
        <h1>Selamat Datang</h1>
        <p class="lead">di PAUDQU Al-Ikhlas</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <div class="row align-items-center g-4">
        <div class="col-12 col-md-6 text-center">
            <img src="<?= htmlspecialchars($hero_img) ?>" alt="Anak PAUD Belajar" class="img-fluid rounded-4 shadow" style="max-height: 320px; width: 100%; object-fit: cover;">
        </div>
        <div class="col-12 col-md-6 text-center text-md-start">
            <h2 class="fw-bold mb-3 text-success" style="font-size: 1.6rem;"><?= htmlspecialchars($hero_title) ?></h2>
            <p class="text-muted" style="font-size: 0.95rem; line-height: 1.75;">
                <?= nl2br(htmlspecialchars($hero_subtitle)) ?>
            </p>
            <a href="about.php" class="btn btn-primary-custom shadow-sm mt-2">Tentang Kami <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<!-- Fitur Unggulan Singkat -->
<section class="py-4 bg-white">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-12 col-md-4">
                <div class="card p-3 h-100 bg-light">
                    <div class="mb-2" style="color: var(--paud-yellow);"><i class="fas fa-book-open fa-2x"></i></div>
                    <h6 class="fw-bold mb-1">Kurikulum Islami</h6>
                    <p class="text-muted small mb-0">Menerapkan nilai-nilai Al-Qur'an sejak usia dini.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-3 h-100 bg-light">
                    <div class="mb-2" style="color: var(--paud-pink);"><i class="fas fa-chalkboard-teacher fa-2x"></i></div>
                    <h6 class="fw-bold mb-1">Guru Profesional</h6>
                    <p class="text-muted small mb-0">Didik oleh tenaga pengajar yang berpengalaman dan penyayang.</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-3 h-100 bg-light">
                    <div class="mb-2" style="color: var(--paud-orange);"><i class="fas fa-child fa-2x"></i></div>
                    <h6 class="fw-bold mb-1">Lingkungan Nyaman</h6>
                    <p class="text-muted small mb-0">Fasilitas belajar dan bermain yang aman dan menyenangkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
