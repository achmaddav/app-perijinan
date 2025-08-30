<?php 
include __DIR__ . '/../layouts/header.php'; 
// require_once '../config/database.php';
?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Laporan Perizinan</h2>

                <!-- Form Filter -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="page" value="laporan_perizinan">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Pilih Bulan</label>
                                    <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($month_filter) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="Pending" <?= ($status_filter === 'Pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="Approved" <?= ($status_filter === 'Approved') ? 'selected' : '' ?>>Approved</option>
                                        <option value="Rejected" <?= ($status_filter === 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Nama Pemohon</label>
                                    <input type="text" name="pemohon" class="form-control" value="<?= htmlspecialchars($pemohon_filter) ?>" placeholder="Cari nama...">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                        <i class="fas fa-search me-2"></i>Tampilkan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Laporan Perizinan -->
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="index.php?page=export_laporan_excel&month=<?= urlencode($month_filter) ?>&status=<?= urlencode($status_filter) ?>&pemohon=<?= urlencode($pemohon_filter) ?>" 
                            class="btn btn-success btn-sm shadow-sm">
                                <i class="fas fa-file-excel me-2"></i> Export Excel
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama Pemohon</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Durasi (Jam)</th>
                                        <th>Approver</th>
                                        <th>Waktu Pengajuan</th>
                                        <th>Aktual Waktu Keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = $offset + 1; // Menyesuaikan nomor urut berdasarkan halaman
                                    foreach ($data as $row): 
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                                            <td><?= htmlspecialchars($row['alasan']) ?></td>
                                            <td class="text-center">
                                                <span class="badge <?= ($row['status'] === 'Pending') ? 'bg-warning text-dark' : ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                                                    <?= htmlspecialchars($row['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['tanggal_rencana_keluar']) ?></td>
                                            <td><?= htmlspecialchars($row['durasi_keluar']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_atasan'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            <td><?= htmlspecialchars($row['total_waktu_keluar']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=laporan_perizinan&page_no=<?= $i ?>&month=<?= htmlspecialchars($month_filter) ?>&status=<?= urlencode($status_filter) ?>&pemohon=<?= urlencode($pemohon_filter) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

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
    /* Efek Hover & Animasi */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover,
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        transition: all 0.3s ease;
    }

    @media print {
    /* Hilangkan sidebar, navbar, tombol */
    .navbar, .sidebar, .btn, .pagination {
        display: none !important;
    }

    /* Biar tabel penuh kertas */
    .content-wrapper {
        margin: 0;
        padding: 0;
    }
}

</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


