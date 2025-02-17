<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">

    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <!-- Konten Utama -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center my-4 text-primary">Riwayat Perizinan</h2>

                <!-- Notifikasi -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $successMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $errorMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="riwayatTable" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <!-- <th>Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dataPerizinan)): ?>
                                        <?php foreach ($dataPerizinan as $index => $izin): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                                <td>
                                                    <?php
                                                        $statusClass = match ($izin['status']) {
                                                            'Approved' => 'badge bg-success',
                                                            'Rejected' => 'badge bg-danger',
                                                            default => 'badge bg-warning text-dark',
                                                        };
                                                    ?>
                                                    <span class="<?= $statusClass; ?>"><?= htmlspecialchars($izin['status']); ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($izin['created_at']); ?></td>
                                                <!-- <td>
                                                    <a href="index.php?page=hapus_perizinan&id=<?= $izin['id']; ?>" 
                                                       class="btn btn-sm btn-danger shadow-sm rounded-3" 
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada riwayat perizinan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php?page=dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include '../app/views/layouts/footer.php'; ?>

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