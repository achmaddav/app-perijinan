<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Konten Utama -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">Riwayat & Status Cuti</h2>

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
                            <table id="data-table-perizinan" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Mulai Cuti</th>
                                        <th>Tanggal Selai Cuti</th>
                                        <th>Lama Cuti (Hari)</th>
                                        <th>Ketua Tim (Tahap ke-1)</th>
                                        <th>Kepala Balai (Tahap ke-2)</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($leaveHistory)): ?>
                                        <?php foreach ($leaveHistory as $index => $leave): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($leave['tanggal_mulai']); ?></td>
                                                <td><?= htmlspecialchars($leave['tanggal_selesai']); ?></td>
                                                <td><?= htmlspecialchars($leave['jumlah_cuti']); ?></td>
                                                <td><?= htmlspecialchars($leave['tahap_1']); ?></td>
                                                <td><?= htmlspecialchars($leave['tahap_2']); ?></td>
                                                <td>
                                                    <?php
                                                        $statusClass = match ($leave['status']) {
                                                            'Disetujui' => 'badge bg-success',
                                                            'Ditolak' => 'badge bg-danger',
                                                            default => 'badge bg-warning text-dark',
                                                        };
                                                    ?>
                                                    <span class="<?= $statusClass; ?>"><?= htmlspecialchars($leave['status']); ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($leave['status'] === 'Disetujui' || $leave['status'] === 'Ditolak'): ?>
                                                        <button class="btn btn-sm btn-secondary shadow-sm rounded-3"
                                                                onclick="showCannotDeleteAlert('<?= $leave['status']; ?>')">
                                                            Hapus
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-danger shadow-sm rounded-3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalKonfirmasiCuti"
                                                                data-id="<?= $leave['id']; ?>"
                                                                data-fromDate="<?= htmlspecialchars($leave['tanggal_mulai']); ?>"
                                                                data-tillDate="<?= htmlspecialchars($leave['tanggal_selesai']); ?>">
                                                            Hapus
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <form id="cetakForm" method="POST" action="?page=cetak_cuti">
                                                        <input type="hidden" name="id" value="<?= $leave['id']; ?>">
                                                        
                                                        <?php if ($leave['status'] === 'Disetujui'): ?>
                                                            <button type="button" onclick="submitFormPDF()" class="btn btn-primary btn-sm shadow-sm rounded-3">
                                                                Cetak
                                                            </button>
                                                        <?php endif; ?>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="/app-perijinan/dashboard_cuti" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard Cuti
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalKonfirmasiCuti" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="modalLabel"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengajuan cuti untuk tanggal:</p>
                <p class="fw-bold" id="leaveDate"></p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="btnHapus" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>


<style>
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
        transition: all 0.3s ease;
    }

    .btn-danger:hover, .btn-outline-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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


