<?php
include 'header_admin.php';

$sql = "SELECT * FROM profil WHERE id = 1";
$result = $conn->query($sql);
$has_profil = $result->num_rows;
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hero_title = isset($_POST['hero_title']) ? $conn->real_escape_string($_POST['hero_title']) : '';
    $hero_subtitle = isset($_POST['hero_subtitle']) ? $conn->real_escape_string($_POST['hero_subtitle']) : '';
    $sejarah = $conn->real_escape_string($_POST['sejarah']);
    $visi = $conn->real_escape_string($_POST['visi']);
    $misi = $conn->real_escape_string($_POST['misi']);
    $alamat_kontak = $conn->real_escape_string($_POST['alamat_kontak']);
    $telepon = $conn->real_escape_string($_POST['telepon']);
    $email = $conn->real_escape_string($_POST['email']);
    $maps = $conn->real_escape_string($_POST['maps']);
    $ig = $conn->real_escape_string($_POST['ig']);
    $tiktok = $conn->real_escape_string($_POST['tiktok']);
    $gambar_lama = isset($data['gambar']) ? $data['gambar'] : '';
    $gambar_baru = $gambar_lama;
    
    $foto_dashboard_lama = isset($data['foto_dashboard']) ? $data['foto_dashboard'] : '';
    $foto_dashboard_baru = $foto_dashboard_lama;

    // Handle File Upload Profil
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
        if ($_FILES['gambar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['gambar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = 'profil_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $upload_path = '../uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    // Hapus gambar lama jika ada
                    if (!empty($gambar_lama) && file_exists('../uploads/' . $gambar_lama)) {
                        unlink('../uploads/' . $gambar_lama);
                    }
                    $gambar_baru = $new_filename;
                } else {
                    $error = "Gagal menyimpan foto profil yang diupload.";
                }
            } else {
                $error = "Format foto profil tidak didukung. Hanya jpg, jpeg, png, gif.";
            }
        } else {
            $err_code = $_FILES['gambar']['error'];
            $err_msg = "Error saat upload foto profil. Kode: " . $err_code;
            if ($err_code == 1 || $err_code == 2) $err_msg = "Ukuran foto profil terlalu besar (Maksimal tergantung server, biasanya 2MB).";
            $error = $err_msg;
        }
    }

    // Handle File Upload Dashboard
    if (isset($_FILES['foto_dashboard']) && $_FILES['foto_dashboard']['error'] !== 4) {
        if ($_FILES['foto_dashboard']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename_dash = $_FILES['foto_dashboard']['name'];
            $ext_dash = strtolower(pathinfo($filename_dash, PATHINFO_EXTENSION));
            
            if (in_array($ext_dash, $allowed)) {
                $new_filename_dash = 'dashboard_' . time() . '_' . rand(1000, 9999) . '.' . $ext_dash;
                $upload_path_dash = '../uploads/' . $new_filename_dash;
                
                if (move_uploaded_file($_FILES['foto_dashboard']['tmp_name'], $upload_path_dash)) {
                    // Hapus gambar lama jika ada
                    if (!empty($foto_dashboard_lama) && file_exists('../uploads/' . $foto_dashboard_lama)) {
                        unlink('../uploads/' . $foto_dashboard_lama);
                    }
                    $foto_dashboard_baru = $new_filename_dash;
                } else {
                    $error = "Gagal menyimpan foto dashboard yang diupload.";
                }
            } else {
                $error = "Format foto dashboard tidak didukung. Hanya jpg, jpeg, png, gif.";
            }
        } else {
            $err_code = $_FILES['foto_dashboard']['error'];
            $err_msg = "Error saat upload foto dashboard. Kode: " . $err_code;
            if ($err_code == 1 || $err_code == 2) $err_msg = "Ukuran foto dashboard terlalu besar (Maksimal tergantung server, biasanya 2MB).";
            $error = $err_msg;
        }
    }

    if (!isset($error)) {
        if ($has_profil == 0) {
            $query_exec = "INSERT INTO profil (id, hero_title, hero_subtitle, sejarah, visi, misi, gambar, foto_dashboard, alamat_kontak, telepon, email, maps, ig, tiktok) VALUES (1, '$hero_title', '$hero_subtitle', '$sejarah', '$visi', '$misi', '$gambar_baru', '$foto_dashboard_baru', '$alamat_kontak', '$telepon', '$email', '$maps', '$ig', '$tiktok')";
        } else {
            $query_exec = "UPDATE profil SET hero_title='$hero_title', hero_subtitle='$hero_subtitle', sejarah='$sejarah', visi='$visi', misi='$misi', gambar='$gambar_baru', foto_dashboard='$foto_dashboard_baru', alamat_kontak='$alamat_kontak', telepon='$telepon', email='$email', maps='$maps', ig='$ig', tiktok='$tiktok' WHERE id=1";
        }
        
        if ($conn->query($query_exec) === TRUE) {
            $_SESSION['pesan'] = "Profil dan Foto Dashboard berhasil diperbarui!";
            header("Location: profil.php");
            exit();
        } else {
            $error = "Error database: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Profil & Foto Dashboard</h3>
</div>

<?php
if (isset($_SESSION['pesan'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['pesan'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['pesan']);
}
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-3"><i class="fas fa-home text-success me-2"></i> Pengaturan Beranda (Home)</h5>
                    <div class="mb-3">
                        <label for="hero_title" class="form-label fw-bold">Judul Utama Beranda</label>
                        <input type="text" class="form-control" id="hero_title" name="hero_title" placeholder="Contoh: Dengan Ilmu, Hidup Jadi Bermutu" value="<?= htmlspecialchars($data['hero_title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="hero_subtitle" class="form-label fw-bold">Teks Deskripsi Beranda</label>
                        <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3" required placeholder="Contoh: Selamat datang di PAUDQU Al-Ikhlas..."><?= htmlspecialchars($data['hero_subtitle'] ?? '') ?></textarea>
                    </div>
                    
                    <hr>
                    <h5 class="fw-bold mb-3"><i class="fas fa-school text-success me-2"></i> Tujuan & Visi Misi</h5>
                    <div class="mb-4">
                        <label for="sejarah" class="form-label fw-bold"><i class="fas fa-flag text-success me-2"></i> Tujuan</label>
                        <textarea class="form-control" id="sejarah" name="sejarah" rows="5" required><?= htmlspecialchars($data['sejarah'] ?? '') ?></textarea>
                        <small class="text-muted">Teks ini akan ditampilkan di halaman About sebagai Tujuan.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="visi" class="form-label fw-bold"><i class="fas fa-bullseye text-success me-2"></i> Visi Sekolah</label>
                            <textarea class="form-control" id="visi" name="visi" rows="6" required><?= htmlspecialchars($data['visi']) ?></textarea>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="misi" class="form-label fw-bold"><i class="fas fa-tasks text-success me-2"></i> Misi Sekolah</label>
                            <textarea class="form-control" id="misi" name="misi" rows="6" required><?= htmlspecialchars($data['misi']) ?></textarea>
                            <small class="text-muted">Gunakan baris baru (Enter) untuk memisahkan setiap poin misi.</small>
                        </div>
                    </div>
                    
                    <hr>
                    <h5 class="fw-bold mb-3"><i class="fas fa-address-book text-success me-2"></i> Informasi Kontak</h5>
                    <div class="mb-3">
                        <label for="alamat_kontak" class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat_kontak" name="alamat_kontak" rows="2" required><?= htmlspecialchars($data['alamat_kontak'] ?? '') ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label fw-bold">Telepon / WhatsApp</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?= htmlspecialchars($data['telepon'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="maps" class="form-label fw-bold">Link Google Maps</label>
                        <input type="url" class="form-control" id="maps" name="maps" placeholder="https://maps.app.goo.gl/..." value="<?= htmlspecialchars($data['maps'] ?? '') ?>">
                        <small class="text-muted">Masukkan link/URL lokasi Anda dari Google Maps.</small>
                    </div>
                    
                    <hr>
                    <h5 class="fw-bold mb-3"><i class="fas fa-hashtag text-success me-2"></i> Sosial Media</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ig" class="form-label fw-bold">Link Instagram</label>
                            <input type="url" class="form-control" id="ig" name="ig" placeholder="https://instagram.com/..." value="<?= htmlspecialchars($data['ig'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tiktok" class="form-label fw-bold">Link TikTok</label>
                            <input type="url" class="form-control" id="tiktok" name="tiktok" placeholder="https://tiktok.com/@..." value="<?= htmlspecialchars($data['tiktok'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <label for="gambar" class="form-label fw-bold"><i class="fas fa-image text-success me-2"></i> Foto Profil / Tentang Kami</label>
                        <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" data-preview="preview_profil">
                        <small class="text-muted d-block mb-3">Foto ini akan muncul di halaman About.</small>
                        
                        <?php $gambar_url = !empty($data['gambar']) ? '../uploads/' . $data['gambar'] : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop'; ?>
                        <img id="preview_profil" src="<?= $gambar_url ?>" class="img-fluid rounded border shadow-sm" alt="Preview Profil" style="max-height: 200px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview_profil', 'gambar')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                    
                    <div class="mb-3 mt-4">
                        <label for="foto_dashboard" class="form-label fw-bold"><i class="fas fa-desktop text-success me-2"></i> Foto Dashboard / Beranda</label>
                        <input class="form-control mb-2" type="file" id="foto_dashboard" name="foto_dashboard" accept="image/*" data-preview="preview_dashboard">
                        <small class="text-muted d-block mb-3">Foto ini akan muncul sebagai hero banner di halaman depan dan dashboard admin.</small>
                        
                        <?php $dashboard_url = !empty($data['foto_dashboard']) ? '../uploads/' . $data['foto_dashboard'] : '../images/hero.png'; ?>
                        <img id="preview_dashboard" src="<?= $dashboard_url ?>" class="img-fluid rounded border shadow-sm" alt="Preview Dashboard" style="max-height: 200px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview_dashboard', 'foto_dashboard')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 rounded-pill"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
