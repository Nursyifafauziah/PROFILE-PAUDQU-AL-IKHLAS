<?php
include 'header_admin.php';

// Mark all as read when opening this page
$conn->query("UPDATE pesan SET status='sudah_dibaca' WHERE status='belum_dibaca'");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Pesan Masuk (Dari Halaman Kontak)</h3>
</div>

<?php
if (isset($_SESSION['pesan_aksi'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['pesan_aksi'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['pesan_aksi']);
}
if (isset($_SESSION['pesan_error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $_SESSION['pesan_error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['pesan_error']);
}
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Pengirim</th>
                        <th width="20%">Email / Kontak</th>
                        <th width="35%">Isi Pesan</th>
                        <th width="15%">Tanggal</th>
                        <th width="5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM pesan ORDER BY tanggal DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($row['email']) ?></a></td>
                                <td>
                                    <?= nl2br(htmlspecialchars($row['isi_pesan'])) ?>
                                    <?php if ($row['status'] == 'dibalas' && !empty($row['balasan'])): ?>
                                        <div class="mt-2 p-2 bg-light border-start border-4 border-success rounded">
                                            <small class="text-success fw-bold"><i class="fas fa-reply me-1"></i> Balasan Anda:</small><br>
                                            <small><?= nl2br(htmlspecialchars($row['balasan'])) ?></small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted d-block"><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></small>
                                    <?php if ($row['status'] == 'dibalas'): ?>
                                        <span class="badge bg-success mt-1"><i class="fas fa-check-circle me-1"></i> Dibalas</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary mt-1">Sudah Dibaca</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] != 'dibalas'): ?>
                                    <button type="button" class="btn btn-sm btn-primary rounded-circle mb-1" data-bs-toggle="modal" data-bs-target="#balasModal<?= $row['id'] ?>" title="Balas Pesan">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <?php endif; ?>
                                    <a href="hapus_pesan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-circle mb-1" onclick="return confirm('Hapus pesan ini secara permanen?');" title="Hapus Pesan"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>

                            <!-- Modal Balas -->
                            <div class="modal fade" id="balasModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="balasModalLabel<?= $row['id'] ?>" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form action="balas_pesan.php" method="POST">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="balasModalLabel<?= $row['id'] ?>">Balas Pesan: <?= htmlspecialchars($row['nama']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Pesan Pengirim:</label>
                                            <div class="p-2 bg-light rounded border">
                                                <?= nl2br(htmlspecialchars($row['isi_pesan'])) ?>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="balasan_teks" class="form-label fw-bold">Pesan Balasan</label>
                                            <textarea class="form-control" name="balasan_teks" rows="4" required placeholder="Ketik balasan Anda di sini..."></textarea>
                                            <small class="text-muted mt-1 d-block">Pesan ini akan disimpan ke database dan sistem akan mencoba mengirimkan email ke <strong><?= htmlspecialchars($row['email']) ?></strong>.</small>
                                        </div>
                                        <input type="hidden" name="id_pesan" value="<?= $row['id'] ?>">
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Kirim Balasan</button>
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pesan masuk.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
