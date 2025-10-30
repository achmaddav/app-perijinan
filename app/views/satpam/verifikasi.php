<?php 
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

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
                                        <th>Nama Pemohon</th>
                                        <th>Rencana Tanggal Keluar</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Verifikasi Keluar</th>
                                        <th>Verifikasi Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($izinList)): ?>
                                        <?php foreach ($izinList as $index => $izin): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($izin['nama_pengaju']); ?></td>
                                                <td><?= htmlspecialchars($izin['tanggal_rencana_keluar']); ?></td>
                                                <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                                <td>
                                                    <?php
                                                        $status = htmlspecialchars($izin['status']);
                                                        switch ($status) {
                                                            case 'Disetujui':
                                                                $badgeClass = 'badge bg-success';
                                                                break;
                                                            case 'Ditolak':
                                                                $badgeClass = 'badge bg-danger';
                                                                break;
                                                            default:
                                                                $badgeClass = 'badge bg-warning text-dark';
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="<?= $badgeClass; ?>"><?= $status; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (empty($izin['tanggal_keluar'])): ?>
                                                        <!-- Tombol untuk membuka modal -->
                                                        <button type="button" class="btn btn-warning btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#verifikasiKeluarModal<?= $izin['id'] ?>">
                                                            Verifikasi Keluar
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="verifikasiKeluarModal<?= $izin['id'] ?>" tabindex="-1" aria-labelledby="verifikasiLabel<?= $izin['id'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="verifikasiLabel<?= $izin['id'] ?>">Konfirmasi Verifikasi</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin memverifikasi keluar untuk <strong><?= htmlspecialchars($izin['nama_pengaju']) ?></strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="/app-perijinan/verify_keluar" method="POST">
                                                                    <input type="hidden" name="perizinan_id" value="<?= $izin['id']; ?>">
                                                                    <button type="submit" class="btn btn-warning">Ya, Verifikasi</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                </form>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Done</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if (!empty($izin['tanggal_keluar']) && empty($izin['tanggal_masuk'])): ?>
                                                        <!-- Tombol untuk membuka modal -->
                                                        <button type="button" class="btn btn-warning btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#verifikasiMasukModal<?= $izin['id'] ?>">
                                                            Verifikasi Masuk
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="verifikasiMasukModal<?= $izin['id'] ?>" tabindex="-1" aria-labelledby="verifikasiLabel<?= $izin['id'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="verifikasiLabel<?= $izin['id'] ?>">Konfirmasi Verifikasi</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin memverifikasi masuk untuk <strong><?= htmlspecialchars($izin['nama_pengaju']) ?></strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="/app-perijinan/verify_masuk" method="POST">
                                                                    <input type="hidden" name="perizinan_id" value="<?= $izin['id']; ?>">
                                                                    <button type="submit" class="btn btn-warning">Ya, Verifikasi</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                </form>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    <?php elseif (!empty($izin['tanggal_keluar']) && !empty($izin['tanggal_masuk'])): ?>
                                                        <span class="badge bg-success">Done</span>
                                                    <?php endif; ?>
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

<!-- Custom Styles -->
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .btn-warning:hover, .btn-secondary:hover, .btn-outline-primary:hover {
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
