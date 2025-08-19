<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Daftar Pengajuan Cuti</h2>

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
                                        <th>Lama Cuti (Hari)</th>
                                        <th>Tanggal Mulai Cuti</th>
                                        <th>Tanggal Selesai Cuti</th>
                                        <th>Alasan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($daftarPengajuanCuti)): ?>
                                        <?php foreach ($daftarPengajuanCuti as $index => $cuti): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($cuti['nama_pengaju']); ?></td>
                                                <td><?= htmlspecialchars($cuti['lama_cuti']); ?></td>
                                                <td><?= htmlspecialchars($cuti['tanggal_mulai']); ?></td>
                                                <td><?= htmlspecialchars($cuti['tanggal_selesai']); ?></td>
                                                <td><?= htmlspecialchars($cuti['alasan']); ?></td>
                                                <td>
                                                    <form action="/app-perijinan/proses_approval_cuti" method="POST">
                                                        <input type="hidden" name="id" value="<?= $cuti['id']; ?>">
                                                        <?php if ($jabatan === 'KTA'): ?>
                                                            <button type="submit" name="status" value="Progress" class="btn btn-success btn-sm rounded-pill shadow-sm">
                                                                <i class="fas fa-check-circle me-1"></i> Setujui
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="submit" name="status" value="Disetujui" class="btn btn-success btn-sm rounded-pill shadow-sm">
                                                                <i class="fas fa-check-circle me-1"></i> Setujui
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm rounded-pill shadow-sm">
                                                            <i class="fas fa-times-circle me-1"></i> Tolak
                                                        </button>
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

                <!-- Tombol Kembali ke Dashboard -->
                <div class="text-center mt-4">
                    <a href="/app-perijinan/dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>


