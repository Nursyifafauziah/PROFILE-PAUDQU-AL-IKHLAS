<?php
include 'header_admin.php';

$sql = "SELECT * FROM profil WHERE id = 1";
$result = $conn->query($sql);
$has_profil = $result->num_rows;
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $logo_lama = isset($data['logo']) ? $data['logo'] : '';
    $logo_baru = $logo_lama;
    
    $favicon_lama = isset($data['favicon']) ? $data['favicon'] : '';
    $favicon_baru = $favicon_lama;

    $error = null;

    // Handle File Upload Logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== 4) {
        if ($_FILES['logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename_logo = $_FILES['logo']['name'];
            $ext_logo = strtolower(pathinfo($filename_logo, PATHINFO_EXTENSION));
            
            if (in_array($ext_logo, $allowed)) {
                $new_filename_logo = 'logo_' . time() . '_' . rand(1000, 9999) . '.' . $ext_logo;
                $upload_path_logo = '../uploads/' . $new_filename_logo;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path_logo)) {
                    // Hapus gambar lama jika ada
                    if (!empty($logo_lama) && file_exists('../uploads/' . $logo_lama)) {
                        unlink('../uploads/' . $logo_lama);
                    }
                    $logo_baru = $new_filename_logo;
                } else {
                    $error = "Gagal menyimpan logo yang diupload.";
                }
            } else {
                $error = "Format logo tidak didukung. Hanya jpg, jpeg, png, gif, webp.";
            }
        } else {
            $err_code = $_FILES['logo']['error'];
            $err_msg = "Error saat upload logo. Kode: " . $err_code;
            if ($err_code == 1 || $err_code == 2) $err_msg = "Ukuran logo terlalu besar (Maksimal tergantung server, biasanya 2MB).";
            $error = $err_msg;
        }
    }

    // Handle File Upload Favicon
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] !== 4) {
        if ($_FILES['favicon']['error'] == 0) {
            $allowed = ['ico', 'png', 'jpg', 'jpeg', 'gif', 'webp'];
            $filename_fav = $_FILES['favicon']['name'];
            $ext_fav = strtolower(pathinfo($filename_fav, PATHINFO_EXTENSION));
            
            if (in_array($ext_fav, $allowed)) {
                $new_filename_fav = 'favicon_' . time() . '_' . rand(1000, 9999) . '.' . $ext_fav;
                $upload_path_fav = '../uploads/' . $new_filename_fav;
                
                if (move_uploaded_file($_FILES['favicon']['tmp_name'], $upload_path_fav)) {
                    // Hapus favicon lama jika ada
                    if (!empty($favicon_lama) && file_exists('../uploads/' . $favicon_lama)) {
                        unlink('../uploads/' . $favicon_lama);
                    }
                    $favicon_baru = $new_filename_fav;
                } else {
                    $error = "Gagal menyimpan favicon yang diupload.";
                }
            } else {
                $error = "Format favicon tidak didukung. Hanya ico, png, jpg, jpeg, gif, webp.";
            }
        } else {
            $err_code = $_FILES['favicon']['error'];
            $err_msg = "Error saat upload favicon. Kode: " . $err_code;
            if ($err_code == 1 || $err_code == 2) $err_msg = "Ukuran favicon terlalu besar (Maksimal tergantung server, biasanya 2MB).";
            $error = $err_msg;
        }
    }

    if (!isset($error)) {
        if ($has_profil == 0) {
            $query_exec = "INSERT INTO profil (id, logo, favicon) VALUES (1, '$logo_baru', '$favicon_baru')";
        } else {
            $query_exec = "UPDATE profil SET logo='$logo_baru', favicon='$favicon_baru' WHERE id=1";
        }
        
        if ($conn->query($query_exec) === TRUE) {
            $_SESSION['pesan'] = "Pengaturan Logo & Favicon berhasil diperbarui!";
            header("Location: pengaturan.php");
            exit();
        } else {
            $error = "Error database: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Pengaturan Logo</h3>
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
                <div class="col-md-6">
                    <div class="mb-4">
                        <label for="logo" class="form-label fw-bold"><i class="fas fa-circle text-success me-2"></i> Logo Sekolah</label>
                        <input class="form-control mb-2" type="file" id="logo" name="logo" accept="image/*" data-preview="preview_logo">
                        <small class="text-muted d-block mb-3">Logo ini akan muncul di ujung kiri atas website (navbar).</small>
                        
                        <?php $logo_url = !empty($data['logo']) ? '../uploads/' . $data['logo'] : '../images/logo.png'; ?>
                        <div class="text-center bg-light border p-3 rounded">
                            <img id="preview_logo" src="<?= $logo_url ?>" class="img-fluid rounded-circle shadow-sm" alt="Preview Logo" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview_logo', 'logo')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Logo</button>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-4">
                        <label for="favicon" class="form-label fw-bold"><i class="fas fa-star text-success me-2"></i> Favicon (Ikon Tab)</label>
                        <input class="form-control mb-2" type="file" id="favicon" name="favicon" accept="image/*,.ico" data-preview="preview_favicon">
                        <small class="text-muted d-block mb-3">Ikon kecil yang muncul di tab browser (disarankan format .ico atau .png persegi 1:1, ukuran kecil).</small>
                        
                        <?php $favicon_url = !empty($data['favicon']) ? '../uploads/' . $data['favicon'] : '../images/favicon.ico'; ?>
                        <div class="text-center bg-light border p-3 rounded">
                            <img id="preview_favicon" src="<?= $favicon_url ?>" class="img-fluid rounded shadow-sm" alt="Preview Favicon" style="width: 64px; height: 64px; object-fit: contain;">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview_favicon', 'favicon')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Favicon</button>
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
