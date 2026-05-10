<?php 
include 'header.php';

if (!isset($_GET['id'])) {
    header("Location: kegiatan.php?type=berita");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM kegiatan WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: kegiatan.php?type=berita");
    exit();
}

$berita = $result->fetch_assoc();
$gambar = !empty($berita['gambar']) ? 'uploads/' . $berita['gambar'] : '';
$tanggal = date('d F Y', strtotime($berita['tanggal']));
?>

<div class="page-header fade-in">
    <div class="container">
        <h1><?= $berita['tipe'] == 'pengumuman' ? 'Pengumuman' : 'Berita' ?></h1>
        <p class="lead">Detail informasi PAUDQU Al-Ikhlas</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <!-- Back button -->
            <a href="kegiatan.php?type=<?= $berita['tipe'] ?>" class="btn btn-outline-success btn-sm rounded-pill mb-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke <?= ucfirst($berita['tipe']) ?>
            </a>

            <!-- Article card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <?php if (!empty($gambar)): ?>
                <div class="text-center" style="background: #f8f9fa;">
                    <img src="<?= htmlspecialchars($gambar) ?>" class="img-fluid" alt="<?= htmlspecialchars($berita['judul']) ?>" style="max-height: 400px; width: auto; object-fit: contain;">
                </div>
                <?php endif; ?>
                
                <div class="card-body p-4">
                    <!-- Badge + Date -->
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge rounded-pill px-2 py-1 me-2" style="background-color: <?= $berita['tipe'] == 'pengumuman' ? 'rgba(40,167,69,0.12)' : 'rgba(13,110,253,0.12)' ?>; color: <?= $berita['tipe'] == 'pengumuman' ? '#28a745' : '#0d6efd' ?>; font-size: 0.72rem;">
                            <i class="fas fa-<?= $berita['tipe'] == 'pengumuman' ? 'bullhorn' : 'newspaper' ?> me-1"></i> <?= ucfirst($berita['tipe']) ?>
                        </span>
                        <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> <?= $tanggal ?></small>
                    </div>
                    
                    <!-- Title -->
                    <h4 class="fw-bold mb-3" style="color: #222; line-height: 1.4;"><?= htmlspecialchars($berita['judul']) ?></h4>
                    
                    <!-- Divider -->
                    <div style="width: 50px; height: 3px; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; margin-bottom: 16px;"></div>
                    
                    <!-- Content -->
                    <div class="text-muted" style="font-size: 0.95rem; line-height: 1.8;">
                        <?= nl2br(htmlspecialchars($berita['konten'] ?? '')) ?>
                    </div>
                </div>
            </div>

            <!-- Related articles -->
            <?php
            $sql_related = "SELECT * FROM kegiatan WHERE tipe = '{$berita['tipe']}' AND id != '$id' ORDER BY tanggal DESC LIMIT 3";
            $result_related = $conn->query($sql_related);
            if ($result_related->num_rows > 0):
            ?>
            <div class="mt-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-th-list me-2 text-success"></i><?= $berita['tipe'] == 'pengumuman' ? 'Pengumuman' : 'Berita' ?> Lainnya</h6>
                <div class="row g-3">
                    <?php while ($rel = $result_related->fetch_assoc()): 
                        $rel_gambar = !empty($rel['gambar']) ? 'uploads/' . $rel['gambar'] : '';
                    ?>
                    <div class="col-12 col-md-4">
                        <a href="detail_berita.php?id=<?= $rel['id'] ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden">
                                <?php if (!empty($rel_gambar)): ?>
                                <img src="<?= htmlspecialchars($rel_gambar) ?>" class="card-img-top" alt="<?= htmlspecialchars($rel['judul']) ?>" style="height: 120px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body p-2">
                                    <small class="fw-bold text-dark" style="font-size: 0.8rem;"><?= htmlspecialchars($rel['judul']) ?></small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
