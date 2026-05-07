<?php
include 'header_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipe = 'pengumuman';
    $judul = $conn->real_escape_string($_POST['judul']);
    $konten = $conn->real_escape_string($_POST['konten']);
    $gambar = '';

    // Handle File Upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'pengumuman_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $new_filename;
            }
        }
    }

    $sql = "INSERT INTO kegiatan (tipe, judul, konten, gambar) VALUES ('$tipe', '$judul', '$konten', '$gambar')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['pesan'] = "Data pengumuman berhasil ditambahkan!";
        header("Location: pengumuman.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Tambah Pengumuman</h3>
    <a href="pengumuman.php" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul Pengumuman</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="konten" class="form-label fw-bold">Isi Pengumuman</label>
                        <textarea class="form-control" id="konten" name="konten" rows="6" required></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gambar" class="form-label fw-bold">Gambar (Opsional)</label>
                        <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" data-preview="preview">
                        <small class="text-muted d-block mb-3">Format diperbolehkan: JPG, PNG, GIF.</small>
                        <img id="preview" src="https://via.placeholder.com/400x300?text=Preview+Gambar" class="img-fluid rounded border shadow-sm" alt="Preview" style="max-height: 250px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview', 'gambar')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-success px-4 rounded-pill"><i class="fas fa-save me-1"></i> Simpan Pengumuman</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
