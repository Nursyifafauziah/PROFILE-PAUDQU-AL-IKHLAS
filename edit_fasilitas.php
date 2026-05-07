<?php
include 'header_admin.php';

if (!isset($_GET['id'])) {
    header("Location: fasilitas.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM fasilitas WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: fasilitas.php");
    exit();
}

$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_fasilitas = $conn->real_escape_string($_POST['nama_fasilitas']);
    $gambar_lama = $data['gambar'];
    
    $query_update = "UPDATE fasilitas SET nama_fasilitas='$nama_fasilitas' ";

    // Handle File Upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'fasilitas_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                // Hapus gambar lama jika ada
                if (!empty($gambar_lama) && file_exists('../uploads/' . $gambar_lama)) {
                    unlink('../uploads/' . $gambar_lama);
                }
                $query_update .= ", gambar='$new_filename' ";
            }
        }
    }

    $query_update .= " WHERE id='$id'";
    
    if ($conn->query($query_update) === TRUE) {
        $_SESSION['pesan'] = "Data fasilitas berhasil diubah!";
        header("Location: fasilitas.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Edit Fasilitas</h3>
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
                        <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" value="<?= htmlspecialchars($data['nama_fasilitas']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gambar" class="form-label fw-bold">Gambar</label>
                        <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" data-preview="preview">
                        <small class="text-muted d-block mb-3">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        
                        <?php $gambar_url = !empty($data['gambar']) ? '../uploads/' . $data['gambar'] : 'https://via.placeholder.com/400x300?text=Preview+Gambar'; ?>
                        <img id="preview" src="<?= $gambar_url ?>" class="img-fluid rounded border shadow-sm" alt="Preview" style="max-height: 250px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview', 'gambar')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 rounded-pill"><i class="fas fa-save me-1"></i> Update Fasilitas</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
