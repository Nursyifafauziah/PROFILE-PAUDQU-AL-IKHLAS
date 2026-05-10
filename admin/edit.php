<?php
include 'header_admin.php';

if (!isset($_GET['id'])) {
    header("Location: guru.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM guru WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: guru.php");
    exit();
}

$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $foto_lama = $data['foto'];
    
    $query_update = "UPDATE guru SET nama='$nama', jabatan='$jabatan', alamat='$alamat' ";

    // Handle File Upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Hapus foto lama jika ada
                if (!empty($foto_lama) && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }
                $query_update .= ", foto='$new_filename' ";
            }
        }
    }

    $query_update .= " WHERE id='$id'";
    
    if ($conn->query($query_update) === TRUE) {
        $_SESSION['pesan'] = "Data guru berhasil diubah!";
        header("Location: guru.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Edit Data Guru</h3>
    <a href="guru.php" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
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
                        <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label fw-bold">Jabatan / Posisi</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= htmlspecialchars($data['jabatan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="foto" class="form-label fw-bold">Foto Profile</label>
                        <input class="form-control mb-2" type="file" id="foto" name="foto" accept="image/*" data-preview="preview">
                        <small class="text-muted d-block mb-3">Biarkan kosong jika tidak ingin mengubah foto.</small>
                        
                        <?php $foto_url = !empty($data['foto']) ? '../uploads/' . $data['foto'] : 'https://via.placeholder.com/300x400?text=Preview+Foto'; ?>
                        <img id="preview" src="<?= $foto_url ?>" class="img-fluid rounded border shadow-sm" alt="Preview" style="max-height: 300px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="triggerCrop('preview', 'foto')"><i class="fas fa-crop-alt"></i> Pangkas / Edit Foto</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 rounded-pill"><i class="fas fa-save me-1"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
