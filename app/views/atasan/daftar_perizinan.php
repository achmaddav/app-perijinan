<?php include '../app/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Daftar Perizinan</h2>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Card Daftar Perizinan -->
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table-perizinan" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengaju</th>
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
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($izin['nama_pengaju']); ?></td>
                                                <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                                <td>
                                                    <?php
                                                        $status = htmlspecialchars($izin['status']);
                                                        $badgeClass = match ($status) {
                                                            'Disetujui' => 'badge bg-success',
                                                            'Ditolak' => 'badge bg-danger',
                                                            default => 'badge bg-warning text-dark',
                                                        };
                                                    ?>
                                                    <span class="<?= $badgeClass; ?>"><?= $status; ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($izin['created_at']); ?></td>
                                                <td>
                                                    <form action="index.php?page=proses_perizinan" method="POST">
                                                        <input type="hidden" name="id" value="<?= $izin['id']; ?>">
                                                        <button type="submit" name="status" value="Disetujui" class="btn btn-success btn-sm rounded-pill shadow-sm">
                                                            <i class="fas fa-check-circle me-1"></i> Setujui
                                                        </button>
                                                        <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm rounded-pill shadow-sm">
                                                            <i class="fas fa-times-circle me-1"></i> Tolak
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada perizinan yang menunggu persetujuan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Kembali ke Dashboard -->
                <div class="text-center mt-4">
                    <a href="index.php?page=dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    /* Hover dan Animasi */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .btn-success:hover, .btn-danger:hover, .btn-outline-primary:hover {
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

<?php include '../app/views/layouts/footer.php'; ?>


