<?php 
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Riwayat Verifikasi</h2>

                <!-- Card Riwayat Verifikasi -->
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
                                        <th>Waktu Keluar</th>
                                        <th>Waktu Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($historyList)): ?>
                                        <?php foreach ($historyList as $index => $izin): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($izin['nama_pemohon']); ?></td>
                                                <td><?= htmlspecialchars($izin['tanggal_rencana_keluar']); ?></td>
                                                <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                                <td><?= htmlspecialchars($izin['waktu_keluar']); ?></td>
                                                <td><?= htmlspecialchars($izin['waktu_masuk']); ?></td>
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
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
