<?php
include 'header_admin.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Guru</h3>
    <a href="tambah.php" class="btn btn-success rounded-pill px-4"><i class="fas fa-plus me-1"></i> Tambah Guru</a>
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
                        <th width="15%">Foto</th>
                        <th width="20%">Nama</th>
                        <th width="20%">Jabatan</th>
                        <th width="25%">Alamat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM guru ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $foto = !empty($row['foto']) ? '../uploads/' . $row['foto'] : 'https://ui-avatars.com/api/?name='.urlencode($row['nama']).'&background=random&size=100';
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" title="Hapus"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data guru.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
