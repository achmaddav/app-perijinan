<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">Daftar Jabatan</h2>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm auto-dismiss">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <a href="/app-perijinan/add_jabatan" class="btn btn-sm btn-success mb-3 shadow-sm">
                            <i class="fas fa-plus me-2"></i> Tambah Jabatan
                        </a>

                        <div class="table-responsive">
                            <table id="data-table-perizinan" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($stmt->rowCount() > 0): ?>
                                        <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($row['kode']); ?></td>
                                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                                <td>
                                                    <a href="index.php?page=edit_jabatan&id=<?= $row['id'] ?>" 
                                                       class="btn btn-warning btn-sm shadow-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" 
                                                        class="btn btn-danger btn-sm shadow-sm btn-delete" 
                                                        data-id="<?= $row['id'] ?>" 
                                                        data-nama="<?= htmlspecialchars($row['nama']) ?>">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">Tidak ada data jabatan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/app-perijinan/dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Global -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Apakah Anda yakin ingin menghapus jabatan <strong id="namaJabatan"></strong>?</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Batal
        </button>
        <a href="#" id="btnConfirmDelete" class="btn btn-danger shadow-sm">
          <i class="fas fa-trash-alt me-1"></i> Ya, Hapus
        </a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<script src="/app-perijinan/assets/js/admin/jabatan.js?v=<?= time(); ?>"></script>
