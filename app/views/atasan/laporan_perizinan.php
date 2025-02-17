<?php 
include '../app/views/layouts/header.php'; 
require_once '../config/database.php';

// Ambil instance database dan koneksi PDO
$database = Database::getInstance();
$pdo = $database->getConnection();

// Konfigurasi Paginasi
$limit = 10;
$page = isset($_GET['page_no']) ? max(1, (int)$_GET['page_no']) : 1;
$offset = max(0, ($page - 1) * $limit);

// Filter Data Berdasarkan Bulan yang Dipilih
$month_filter = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$status_filter = $_GET['status'] ?? '';
$pemohon_filter = $_GET['pemohon'] ?? '';

// Query Data dengan JOIN ke tabel users
$query = "SELECT p.id, u.nama AS nama_pemohon, p.alasan, p.status, 
                 p.tanggal_rencana_keluar, p.durasi_keluar, 
                 a.nama AS nama_atasan, p.created_at 
          FROM perizinan p
          JOIN users u ON p.user_id = u.id
          LEFT JOIN users a ON p.approved_by = a.id
          WHERE DATE_FORMAT(p.created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
          AND p.approved_by = :user_id";

$params = [':user_id' => $user_id];

if (!empty($status_filter)) {
    $query .= " AND p.status = :status";
    $params[':status'] = $status_filter;
}

if (!empty($pemohon_filter)) {
    $query .= " AND u.nama LIKE :pemohon";
    $params[':pemohon'] = "%$pemohon_filter%";
}

$query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

if (!empty($status_filter)) {
    $stmt->bindValue(':status', $status_filter, PDO::PARAM_STR);
}

if (!empty($pemohon_filter)) {
    $stmt->bindValue(':pemohon', "%$pemohon_filter%", PDO::PARAM_STR);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung Total Data untuk Paginasi
$count_query = "SELECT COUNT(*) 
                FROM perizinan p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN users a ON p.approved_by = a.id
                WHERE DATE_FORMAT(p.created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
                AND p.approved_by = :user_id";

if (!empty($status_filter)) {
    $count_query .= " AND p.status = :status";
}

if (!empty($pemohon_filter)) {
    $count_query .= " AND u.nama LIKE :pemohon";
}

$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

if (!empty($status_filter)) {
    $count_stmt->bindValue(':status', $status_filter, PDO::PARAM_STR);
}

if (!empty($pemohon_filter)) {
    $count_stmt->bindValue(':pemohon', "%$pemohon_filter%", PDO::PARAM_STR);
}

$count_stmt->execute();
$total_rows = $count_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);
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
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
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
