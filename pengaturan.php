<?php include 'header.php'; ?>

<div class="page-header fade-in">
    <div class="container">
        <h1 class="display-4 fw-bold">Pengaturan Pengunjung</h1>
        <p class="lead">Sesuaikan tampilan website sesuai kenyamanan Anda.</p>
    </div>
</div>

<div class="container py-5 fade-in" style="min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-3"><i class="fas fa-paint-roller text-success me-2"></i> Tampilan Website</h4>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Mode Gelap (Dark Mode)</h6>
                            <small class="text-muted">Ubah warna latar menjadi gelap agar lebih nyaman di mata saat malam hari.</small>
                        </div>
                        <div class="form-check form-switch fs-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle">
                        </div>
                    </div>
                    
                    <div class="alert alert-success border-0 rounded-3 mt-4">
                        <i class="fas fa-info-circle me-2"></i> Pengaturan ini akan disimpan di perangkat Anda dan tidak memengaruhi pengunjung lain.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
