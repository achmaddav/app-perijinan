<?php 
include '../app/views/layouts/header.php'; 
require_once '../config/database.php';
?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Laporan Perizinan</h2>

                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="laporan_perizinan">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <label>Pilih Bulan</label>
                            <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($month_filter) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua</option>
                                <option value="Pending" <?= ($status_filter === 'Pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="Approved" <?= ($status_filter === 'Approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="Rejected" <?= ($status_filter === 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Nama Pemohon</label>
                            <input type="text" name="pemohon" class="form-control" value="<?= htmlspecialchars($pemohon_filter) ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                </form>

                <div class="card mt-4">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemohon</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal Keluar</th>
                                <th>Durasi</th>
                                <th>Atasan</th>
                                <th>Waktu Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = $offset + 1; // Menyesuaikan nomor urut berdasarkan halaman
                            foreach ($data as $row): 
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                                    <td><?= htmlspecialchars($row['alasan']) ?></td>
                                    <td>
                                        <span class="badge <?= ($row['status'] === 'Pending') ? 'bg-warning' : ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['tanggal_rencana_keluar']) ?></td>
                                    <td><?= htmlspecialchars($row['durasi_keluar']) ?> menit</td>
                                    <td><?= htmlspecialchars($row['nama_atasan'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=laporan_perizinan&page_no=<?= $i ?>&month=<?= htmlspecialchars($month_filter) ?>&status=<?= urlencode($status_filter) ?>&pemohon=<?= urlencode($pemohon_filter) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

                <a href="index.php?page=dashboard" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
            </div>
        </section>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>
