<?php
include 'header.php';

// Fetch gallery images from database
$sql = "SELECT * FROM galeri ORDER BY tanggal_upload DESC";
$result = $conn->query($sql);
?>

<div class="page-header fade-in">
    <div class="container">
        <h1 class="display-4 fw-bold">Galeri</h1>
        <p class="lead">Dokumentasi momen berharga dan aktivitas pembelajaran di PAUDQU Al-Ikhlas</p>
    </div>
</div>

<div class="container py-4">
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3" id="gallery-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col fade-in">
                    <div class="gallery-card rounded-3 overflow-hidden">
                        <div class="gallery-img-wrapper">
                            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                                 class="gallery-img" 
                                 alt="Foto Galeri"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#imageModal"
                                 onclick="showImageModal(this.src)">
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 fade-in">
                <i class="fas fa-camera fa-4x text-muted mb-3 opacity-50"></i>
                <h4 class="text-muted">Belum ada foto</h4>
                <p class="text-muted">Galeri sedang dalam tahap pembaruan. Silakan kembali lagi nanti.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid rounded shadow-lg" alt="Foto Galeri">
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
}
</script>

<style>
.gallery-card {
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    background: #fff;
}
.gallery-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}
.gallery-img-wrapper {
    position: relative;
    padding-top: 75%; /* 4:3 aspect ratio */
    overflow: hidden;
}
.gallery-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.gallery-card:hover .gallery-img {
    transform: scale(1.05);
}
</style>

<?php include 'footer.php'; ?>
