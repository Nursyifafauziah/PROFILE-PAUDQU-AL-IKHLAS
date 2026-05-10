<?php 
include 'header.php'; 

$sql_profil = "SELECT * FROM profil WHERE id = 1";
$result_profil = $conn->query($sql_profil);
$profil = $result_profil ? $result_profil->fetch_assoc() : [];

$alamat = !empty($profil['alamat_kontak']) ? htmlspecialchars($profil['alamat_kontak']) : 'Jl. Pendidikan No. 123, Kelurahan Bahagia, Kecamatan Damai, Kota Sejahtera 12345';
$telepon = !empty($profil['telepon']) ? htmlspecialchars($profil['telepon']) : '+62 812 3456 7890';
$email = !empty($profil['email']) ? htmlspecialchars($profil['email']) : 'info@paudqualikhlas.sch.id';
$maps = !empty($profil['maps']) ? $profil['maps'] : ''; 
$ig = !empty($profil['ig']) ? htmlspecialchars($profil['ig']) : '';
$tiktok = !empty($profil['tiktok']) ? htmlspecialchars($profil['tiktok']) : '';

// Format number for WhatsApp
$wa_number = preg_replace('/[^0-9]/', '', $telepon);
if (substr($wa_number, 0, 1) == '0') {
    $wa_number = '62' . substr($wa_number, 1);
}
?>

<div class="page-header fade-in">
    <div class="container">
        <h1>Hubungi Kami</h1>
        <p class="lead">Kami siap membantu menjawab pertanyaan Anda seputar PAUDQU Al-Ikhlas.</p>
    </div>
</div>

<div class="container py-4 fade-in">
    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="p-3 shadow-sm rounded-4 h-100 bg-white border">
                <h6 class="fw-bold mb-3 text-success">Informasi Kontak</h6>
                
                <div class="d-flex mb-3">
                    <div class="text-success me-3"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                    <div>
                        <small class="fw-bold d-block mb-1">Alamat</small>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;"><?= nl2br($alamat) ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-3">
                    <div class="text-success me-3"><i class="fas fa-phone-alt fa-lg"></i></div>
                    <div>
                        <small class="fw-bold d-block mb-1">Telepon/WhatsApp</small>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;"><?= $telepon ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-3">
                    <div class="text-success me-3"><i class="fas fa-envelope fa-lg"></i></div>
                    <div>
                        <small class="fw-bold d-block mb-1">Email</small>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;"><?= $email ?></p>
                    </div>
                </div>
                
                <hr class="my-3">
                <?php if (!empty($maps)): ?>
                <div class="mb-3 text-center">
                    <a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn btn-success btn-sm rounded-pill w-100"><i class="fas fa-map-marked-alt me-2"></i> Buka Lokasi di Google Maps</a>
                </div>
                <hr class="my-3">
                <?php endif; ?>
                <small class="fw-bold d-block mb-2">Ikuti Kami</small>
                <div>
                    <?php if (!empty($ig)): ?>
                    <a href="<?= $ig ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-circle me-1"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($tiktok)): ?>
                    <a href="<?= $tiktok ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-circle"><i class="fab fa-tiktok"></i></a>
                    <?php endif; ?>
                    <?php if (empty($ig) && empty($tiktok)): ?>
                    <p class="text-muted small mb-0">Belum ada tautan sosial media.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="p-3 shadow-sm rounded-4 h-100 bg-white border">
                <h6 class="fw-bold mb-3 text-success">Kirim Pesan</h6>
                <form onsubmit="sendToWhatsApp(event)">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" class="form-control rounded-3" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                    </div>

                    <div class="mb-3">
                        <label for="pesan" class="form-label fw-bold text-muted small">Pesan</label>
                        <textarea class="form-control rounded-3" id="pesan" name="pesan" rows="3" placeholder="Tulis pesan Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 mt-1"><i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp</button>
                </form>

                <script>
                function sendToWhatsApp(event) {
                    event.preventDefault();
                    var nama = document.getElementById('nama').value;
                    var pesan = document.getElementById('pesan').value;
                    
                    var text = "Halo, saya *" + nama + "*.\n\n" + pesan;
                    var encodedText = encodeURIComponent(text);
                    var waUrl = "https://wa.me/<?= $wa_number ?>?text=" + encodedText;
                    
                    window.open(waUrl, '_blank');
                }
                </script>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
