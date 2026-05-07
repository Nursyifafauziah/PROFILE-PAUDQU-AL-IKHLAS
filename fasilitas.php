<?php
include 'header_admin.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Fasilitas</h3>
    <a href="tambah_fasilitas.php" class="btn btn-success rounded-pill px-4"><i class="fas fa-plus me-1"></i> Tambah Fasilitas</a>
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
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Gambar</th>
                        <th width="50%">Nama Fasilitas</th>
                        <th width="25%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM fasilitas ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $gambar = !empty($row['gambar']) ? '../uploads/' . $row['gambar'] : 'https://via.placeholder.com/150?text=No+Image';
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($row['nama_fasilitas']) ?>" class="img-thumbnail rounded" style="width: 100px; height: 70px; object-fit: cover;">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama_fasilitas']) ?></td>
                                <td class="text-center">
                                    <a href="edit_fasilitas.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_fasilitas.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Hapus fasilitas ini?');" title="Hapus"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data fasilitas.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
