<?php
include 'header_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gambar_baru = '';

    // Handle File Upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
        if ($_FILES['gambar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['gambar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = 'galeri_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $upload_path = '../uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    $gambar_baru = $new_filename;
                } else {
                    $error = "Gagal menyimpan foto galeri yang diupload.";
                }
            } else {
                $error = "Format foto tidak didukung. Hanya jpg, jpeg, png, gif, webp.";
            }
        } else {
            $err_code = $_FILES['gambar']['error'];
            $err_msg = "Error saat upload foto. Kode: " . $err_code;
            if ($err_code == 1 || $err_code == 2) $err_msg = "Ukuran foto terlalu besar.";
            $error = $err_msg;
        }
    } else {
        $error = "Foto galeri wajib diunggah.";
    }

    if (!isset($error)) {
        $query_exec = "INSERT INTO galeri (gambar) VALUES ('$gambar_baru')";
        
        if ($conn->query($query_exec) === TRUE) {
            $_SESSION['pesan'] = "Foto berhasil ditambahkan ke galeri!";
            header("Location: galeri.php");
            exit();
        } else {
            $error = "Error database: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Tambah Foto Galeri</h3>
    <a href="galeri.php" class="btn btn-secondary rounded-pill px-4 shadow-sm"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="gambar" class="form-label fw-bold"><i class="fas fa-image text-primary me-2"></i> Upload Foto</label>
                        <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" data-preview="preview_galeri" required>
                        <small class="text-muted d-block mb-3">Pilih foto terbaik untuk ditampilkan di galeri.</small>
                        
                        <div class="text-center bg-light border p-3 rounded">
                            <img id="preview_galeri" src="https://images.unsplash.com/photo-1596464716127-f2a82984de30?w=800&q=80" class="img-fluid rounded shadow-sm" alt="Preview" style="max-height: 250px; width: 100%; object-fit: cover;">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview_galeri', 'gambar')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            
            <hr class="mt-2 mb-4">
            <div class="text-end">
                <button type="submit" class="btn btn-success px-4 rounded-pill"><i class="fas fa-save me-1"></i> Simpan ke Galeri</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
