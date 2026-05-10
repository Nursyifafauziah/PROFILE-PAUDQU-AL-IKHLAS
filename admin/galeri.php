<?php
include 'header_admin.php';

$sql = "SELECT * FROM galeri ORDER BY tanggal_upload DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Galeri</h3>
    <a href="tambah_galeri.php" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-plus me-2"></i> Tambah Foto Baru</a>
</div>

<?php
if (isset($_SESSION['pesan'])) {
    echo '<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ' . $_SESSION['pesan'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['pesan']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            ' . $_SESSION['error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error']);
}
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative group">
                            <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="Foto Galeri" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <small class="text-muted"><i class="fas fa-clock me-1"></i> <?= date('d M Y, H:i', strtotime($row['tanggal_upload'])) ?></small>
                            </div>
                            <div class="card-footer bg-white border-0 text-end pb-3 pe-3">
                                <a href="hapus_galeri.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?');"><i class="fas fa-trash-alt me-1"></i> Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <img src="https://illustrations.popsy.co/amber/camera.svg" alt="No Data" class="mb-3" style="width: 150px; opacity: 0.7;">
                        <h5 class="text-muted">Belum ada foto di galeri</h5>
                        <p class="text-muted small">Mulai unggah momen kegiatan dan dokumentasi sekolah Anda.</p>
                        <a href="tambah_galeri.php" class="btn btn-primary mt-2 rounded-pill"><i class="fas fa-plus me-1"></i> Unggah Sekarang</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
