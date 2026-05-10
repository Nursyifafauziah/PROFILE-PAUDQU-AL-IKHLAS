<?php 
include 'header.php'; 

// Ambil data profil
$sql = "SELECT * FROM profil WHERE id = 1";
$result = $conn->query($sql);
$profil = $result ? $result->fetch_assoc() : null;
?>

<div class="page-header fade-in">
    <div class="container">
        <h1>Tentang Kami</h1>
        <p class="lead">Mengenal lebih dekat PAUDQU Al-Ikhlas</p>
    </div>
</div>

<!-- Section 1: Tujuan + Foto -->
<div class="container py-4 fade-in">
    <div class="row align-items-center g-4">
        <div class="col-12 col-lg-6 text-center">
            <?php $gambar_profil = !empty($profil['gambar']) ? 'uploads/' . $profil['gambar'] : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop'; ?>
            <img src="<?= htmlspecialchars($gambar_profil) ?>" alt="Tentang Sekolah" class="img-fluid rounded-4 shadow" style="max-height: 320px; width: 100%; object-fit: cover;">
        </div>
        <div class="col-12 col-lg-6">
            <div style="display: inline-block; background: linear-gradient(135deg, #28a745, #20c997); color: white; font-size: 0.75rem; font-weight: 700; padding: 5px 14px; border-radius: 50px; margin-bottom: 12px; letter-spacing: 0.5px;">
                <i class="fas fa-flag me-1"></i> TUJUAN KAMI
            </div>
            <h3 class="fw-bold mb-2" style="color: #222; line-height: 1.35;">
                Membentuk Generasi Qurani yang Berakhlak Mulia
            </h3>
            <div style="width: 50px; height: 3px; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; margin-bottom: 14px;"></div>
            <p class="text-muted" style="font-size: 0.95rem; line-height: 1.75;">
                <?= nl2br(htmlspecialchars($profil['sejarah'] ?? 'Data belum diisi.')) ?>
            </p>
        </div>
    </div>
</div>

<!-- Section 2: Visi -->
<section class="py-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);">
    <div class="container fade-in">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 text-center">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; margin-bottom: 14px; box-shadow: 0 6px 18px rgba(40,167,69,0.25);">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <h4 class="fw-bold mb-1" style="color: #222;">Visi Sekolah</h4>
                <div style="width: 40px; height: 3px; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; margin: 0 auto 16px;"></div>
                <div style="background: white; border-radius: 12px; padding: 20px 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.05); position: relative;">
                    <div style="position: absolute; top: -8px; left: 20px; font-size: 3rem; color: #28a745; opacity: 0.12; font-family: Georgia, serif; line-height: 1;">"</div>
                    <p class="mb-0" style="font-size: 0.95rem; line-height: 1.75; color: #444; font-style: italic;">
                        <?= nl2br(htmlspecialchars($profil['visi'] ?? 'Data belum diisi.')) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Misi -->
<section class="py-4">
    <div class="container fade-in">
        <div class="text-center mb-4">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #fd7e14); border-radius: 50%; margin-bottom: 14px; box-shadow: 0 6px 18px rgba(255,193,7,0.25);">
                <i class="fas fa-tasks text-white"></i>
            </div>
            <h4 class="fw-bold mb-1" style="color: #222;">Misi Sekolah</h4>
            <div style="width: 40px; height: 3px; background: linear-gradient(90deg, #ffc107, #fd7e14); border-radius: 2px; margin: 0 auto 8px;"></div>
            <p class="text-muted mx-auto small" style="max-width: 420px;">Langkah nyata yang kami tempuh untuk mewujudkan visi sekolah.</p>
        </div>

        <div class="row g-2 justify-content-center">
            <?php
            $misi_text = $profil['misi'] ?? 'Data belum diisi.';
            $misi_lines = array_filter(array_map('trim', explode("\n", $misi_text)));
            $misi_num = 1;
            foreach ($misi_lines as $line):
                if (empty($line)) continue;
                $clean_line = preg_replace('/^\d+[\.\)\-]\s*/', '', $line);
            ?>
                <div class="col-12 col-md-6">
                    <div class="d-flex align-items-start p-3 rounded-3 h-100 misi-item" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 8px; color: white; font-weight: 700; font-size: 0.8rem;">
                            <?= $misi_num++ ?>
                        </div>
                        <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.65; padding-top: 4px;">
                            <?= htmlspecialchars($clean_line) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.misi-item:hover {
    border-color: #28a745 !important;
    box-shadow: 0 3px 12px rgba(40,167,69,0.08);
    transform: translateY(-1px);
}
</style>

<?php include 'footer.php'; ?>
