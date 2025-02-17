<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">

    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <!-- Konten Utama -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center my-4">Riwayat Perizinan</h2>

                <!-- Notifikasi -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?= $successMessage; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= $errorMessage; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="riwayatTable" class="table table-striped table-bordered text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dataPerizinan)): ?>
                                        <?php foreach ($dataPerizinan as $index => $izin): ?>
                                            <tr>
                                                <td class="align-middle"><?= $index + 1; ?></td>
                                                <td class="align-middle"><?= htmlspecialchars($izin['alasan']); ?></td>
                                                <td class="align-middle">
                                                    <?php
                                                        $statusClass = match ($izin['status']) {
                                                            'Approved' => 'badge bg-success',
                                                            'Rejected' => 'badge bg-danger',
                                                            default => 'badge bg-warning text-dark',
                                                        };
                                                    ?>
                                                    <span class="<?= $statusClass; ?>"><?= htmlspecialchars($izin['status']); ?></span>
                                                </td>
                                                <td class="align-middle"><?= htmlspecialchars($izin['created_at']); ?></td>
                                                <td class="align-middle">
                                                    <a href="index.php?page=hapus_perizinan&id=<?= $izin['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada riwayat perizinan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="index.php?page=dashboard" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include '../app/views/layouts/footer.php'; ?>
