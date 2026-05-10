<?php 
include 'header.php'; 
$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : 'kegiatan';
$title = ucfirst($type);
?>

<div class="page-header fade-in">
    <div class="container">
        <h1><?= $title == 'Kegiatan' ? 'Semua Kegiatan' : $title ?></h1>
        <p class="lead">Informasi terbaru seputar <?= $title ?> PAUDQU Al-Ikhlas.</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <?php
    if ($type == 'pengumuman' || $type == 'berita') {
        $sql = "SELECT * FROM kegiatan WHERE tipe = '$type' ORDER BY tanggal DESC";
    } else {
        $sql = "SELECT * FROM kegiatan ORDER BY tanggal DESC";
    }
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="row g-3 justify-content-center">';
        while ($row = $result->fetch_assoc()) {
                if ($row['tipe'] == 'pengumuman') {
                    // Pengumuman: portrait/square card for poster style
                    $has_gambar = !empty($row['gambar']);
                    ?>
                    <div class="col-6 col-sm-4 col-md-3">
                        <a href="detail_berita.php?id=<?= $row['id'] ?>" class="text-decoration-none pengumuman-card-link">
                            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden pengumuman-card">
                                <?php if ($has_gambar): ?>
                                <div class="pengumuman-img-wrap">
                                    <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
                                    <div class="pengumuman-overlay">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Baca Selengkapnya</span>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="pengumuman-img-wrap" style="background: linear-gradient(135deg, #e8f5e9, #f0fdf4);">
                                    <div class="pengumuman-no-img">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <div class="pengumuman-overlay">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Baca Selengkapnya</span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="card-body p-2 text-center">
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.78rem; line-height: 1.3;"><?= htmlspecialchars($row['judul']) ?></h6>
                                    <small class="text-success fw-bold" style="font-size: 0.7rem;">Lihat Detail <i class="fas fa-arrow-right" style="font-size: 0.6rem;"></i></small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                } else {
                    // Berita: standard card with "Baca Selengkapnya"
                    $gambar = !empty($row['gambar']) ? 'uploads/' . $row['gambar'] : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop';
                    ?>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                            <img src="<?= htmlspecialchars($gambar) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul']) ?>" style="height: 180px; object-fit: cover;">
                            <div class="card-body p-3 d-flex flex-column">
                                <small class="text-muted mb-1" style="font-size: 0.72rem;"><i class="fas fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
                                <h6 class="card-title fw-bold text-success mb-2" style="font-size: 0.9rem;"><?= htmlspecialchars($row['judul']) ?></h6>
                                <p class="card-text text-muted mb-3" style="font-size: 0.82rem; flex-grow: 1;"><?= substr(strip_tags($row['konten'] ?? ''), 0, 80) ?>...</p>
                                <a href="detail_berita.php?id=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm rounded-pill w-100">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        echo '</div>';
    } else {
        echo '<p class="text-center text-muted">Belum ada ' . strtolower($title) . ' saat ini.</p>';
    }
    ?>
</div>

<?php include 'footer.php'; ?>

<style>
/* Pengumuman card styles */
.pengumuman-card {
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.pengumuman-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}
.pengumuman-img-wrap {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
}
.pengumuman-img-wrap img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.pengumuman-card:hover .pengumuman-img-wrap img {
    transform: scale(1.08);
}
.pengumuman-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.45);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
    gap: 6px;
    z-index: 2;
}
.pengumuman-overlay i {
    font-size: 1.4rem;
}
.pengumuman-overlay span {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}
.pengumuman-card:hover .pengumuman-overlay {
    opacity: 1;
}
.pengumuman-no-img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #28a745;
    opacity: 0.3;
    font-size: 2.5rem;
}
.pengumuman-card-link:hover .text-success {
    text-decoration: underline !important;
}
</style>
