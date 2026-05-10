<?php include 'header.php'; ?>

<div class="page-header fade-in">
    <div class="container">
        <h1>Fasilitas Sekolah</h1>
        <p class="lead">Sarana dan prasarana penunjang kegiatan belajar mengajar.</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <div class="row g-3 justify-content-center">
        <?php
        $sql = "SELECT * FROM fasilitas ORDER BY id ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $gambar = !empty($row['gambar']) ? 'uploads/' . $row['gambar'] : 'https://images.unsplash.com/photo-1588075592446-265fd1e6e76f?q=80&w=2072&auto=format&fit=crop';
                ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <img src="<?= htmlspecialchars($gambar) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama_fasilitas']) ?>" style="height: 160px; object-fit: cover;">
                        <div class="card-body p-3 text-center">
                            <h6 class="card-title fw-bold text-success mb-0" style="font-size: 0.88rem;"><?= htmlspecialchars($row['nama_fasilitas']) ?></h6>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center"><p class="text-muted">Data fasilitas belum tersedia.</p></div>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
