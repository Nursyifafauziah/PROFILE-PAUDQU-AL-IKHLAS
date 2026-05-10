<?php
include 'header_admin.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Berita / Kegiatan</h3>
    <a href="tambah_kegiatan.php" class="btn btn-success rounded-pill px-4"><i class="fas fa-plus me-1"></i> Tambah Kegiatan</a>
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
                        <th width="15%">Tipe</th>
                        <th width="65%">Judul</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM kegiatan WHERE tipe='berita' ORDER BY tanggal DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $badge_class = 'bg-primary';
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span class="badge <?= $badge_class ?> rounded-pill px-3">Berita</span></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="text-center">
                                    <a href="edit_kegiatan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_kegiatan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Hapus data ini?');" title="Hapus"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data kegiatan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
