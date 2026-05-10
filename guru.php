<?php include 'header.php'; ?>

<div class="page-header fade-in">
    <div class="container">
        <h1>Tenaga Pendidik</h1>
        <p class="lead">Guru-guru profesional dan penyayang di PAUDQU Al-Ikhlas.</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <div class="row g-3 justify-content-center">
        <?php
        $query = "SELECT * FROM guru ORDER BY id DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foto = !empty($row['foto']) ? 'uploads/' . $row['foto'] : 'https://ui-avatars.com/api/?name='.urlencode($row['nama']).'&background=random&size=300';
                ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <img src="<?= htmlspecialchars($foto) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center p-3">
                            <h6 class="card-title fw-bold text-success mb-1" style="font-size: 0.9rem;"><?= htmlspecialchars($row['nama']) ?></h6>
                            <p class="text-muted mb-1" style="font-size: 0.78rem;"><?= htmlspecialchars($row['jabatan']) ?></p>
                            <p class="text-muted mb-0" style="font-size: 0.75rem;"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center"><p class="text-muted">Data guru belum tersedia.</p></div>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
