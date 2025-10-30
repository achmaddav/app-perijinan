<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">Kalender Tahun <?= htmlspecialchars($_GET['year'] ?? date('Y')) ?></h2>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="calendarTable" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hari Kerja</th>
                                        <th>Tanggal Merah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data)): ?>
                                        <?php foreach ($data as $index => $row): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                                                <td>
                                                    <?php if ($row['is_weekend']): ?>
                                                        <span class="badge bg-secondary text-dark">Tidak</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Ya</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['is_dayoff']): ?>
                                                        <span class="badge bg-success">Ya</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tidak</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">Tidak ada data.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                            <form method="get" class="d-inline-block">
                                <input type="hidden" name="page" value="calendar">
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text fw-bold">Pilih tahun :</span>
                                    <input type="number" 
                                        name="year" 
                                        value="<?= $_GET['year'] ?? date('Y') ?>" 
                                        class="form-control text-center">
                                    <button type="submit" class="btn btn-primary">Lihat</button>
                                </div>
                            </form>

                            <a href="index.php?page=generate_calendar" class="btn btn-success shadow-sm rounded-3">
                                <i class="fas fa-calendar-plus me-2"></i> Generate Kalender
                            </a>

                            <!-- Tombol Synchronize -->
                            <form id="form-sync" method="post" action="index.php?page=calendar_sync">
                                <input type="hidden" name="year" value="<?= $_GET['year'] ?? date('Y') ?>">
                                <button type="button" class="btn btn-warning shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalSync">
                                    <i class="fas fa-sync me-2"></i> Synchronize Dayoff
                                </button>
                            </form>

                            <!-- Tombol Unsynchronize -->
                            <form id="form-unsync" method="post" action="index.php?page=calendar_unsync">
                                <input type="hidden" name="year" value="<?= $_GET['year'] ?? date('Y') ?>">
                                <button type="button" class="btn btn-secondary shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalUnsync">
                                    <i class="fas fa-undo me-2"></i> Unsynchronize Dayoff
                                </button>
                            </form>
                        </div>
                    </div>
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

<!-- Modal Synchronize -->
<div class="modal fade" id="modalSync" tabindex="-1" aria-labelledby="modalSyncLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="modalSyncLabel"><i class="fas fa-sync me-2"></i> Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin <strong>Synchronize Dayoff</strong> untuk tahun <b><?= $_GET['year'] ?? date('Y') ?></b>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" onclick="document.getElementById('form-sync').submit()">Ya, Synchronize</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Unsynchronize -->
<div class="modal fade" id="modalUnsync" tabindex="-1" aria-labelledby="modalUnsyncLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="modalUnsyncLabel"><i class="fas fa-undo me-2"></i> Konfirmasi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin <strong>Unsynchronize Dayoff</strong> untuk tahun <b><?= $_GET['year'] ?? date('Y') ?></b>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('form-unsync').submit()">Ya, Unsynchronize</button>
      </div>
    </div>
  </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .alert {
        animation: fadeIn 0.5s, fadeOut 0.5s 3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
