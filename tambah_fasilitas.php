<?php
include 'header_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_fasilitas = $conn->real_escape_string($_POST['nama_fasilitas']);
    $deskripsi = '';
    $gambar = '';

    // Handle File Upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'fasilitas_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $new_filename;
            }
        }
    }

    $sql = "INSERT INTO fasilitas (nama_fasilitas, deskripsi, gambar) VALUES ('$nama_fasilitas', '$deskripsi', '$gambar')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['pesan'] = "Fasilitas berhasil ditambahkan!";
        header("Location: fasilitas.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Tambah Fasilitas</h3>
    <a href="fasilitas.php" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
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
                        <label for="nama_fasilitas" class="form-label fw-bold">Nama Fasilitas</label>
                        <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gambar" class="form-label fw-bold">Foto Fasilitas</label>
                        <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" data-preview="preview" required>
                        <small class="text-muted d-block mb-3">Format diperbolehkan: JPG, PNG, GIF. Wajib diisi.</small>
                        <img id="preview" src="https://via.placeholder.com/400x300?text=Preview+Gambar" class="img-fluid rounded border shadow-sm" alt="Preview" style="max-height: 250px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview', 'gambar')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-success px-4 rounded-pill"><i class="fas fa-save me-1"></i> Simpan Fasilitas</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
