<?php 
include '../app/views/layouts/header.php'; 
require_once "../app/models/PerizinanModel.php";
$perizinanModel = new PerizinanModel(Database::getInstance()->getConnection());
$izinList = $perizinanModel->getApprovedRequests();
?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Daftar Perizinan</h2>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
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
                                            <td class="text-center"><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($izin['nama_pengaju']); ?></td>
                                            <td><?= htmlspecialchars($izin['tanggal_rencana_keluar']); ?></td>
                                            <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                            <td>
                                                <span class="badge bg-success"><?= htmlspecialchars($izin['status']); ?></span>
                                            </td>
                                            <td class="text-center">
                                            <?php if (empty($izin['tanggal_keluar'])) { ?>
                                                <form action="index.php?page=verify_keluar" method="POST">
                                                    <input type="hidden" name="perizinan_id" value="<?= $izin['id']; ?>">
                                                    <button type="submit" class="btn btn-warning">Verifikasi Keluar</button>
                                                </form>
                                            <?php } else { ?>
                                                <span class="badge bg-success">Done</span>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center">
                                            <?php if (!empty($izin['tanggal_keluar']) && empty($izin['tanggal_masuk'])) { ?>
                                                <form action="index.php?page=verify_masuk" method="POST">
                                                    <input type="hidden" name="perizinan_id" value="<?= $izin['id']; ?>">
                                                    <button type="submit" class="btn btn-secondary">Verifikasi Masuk</button>
                                                </form>
                                            <?php } elseif (!empty($izin['tanggal_keluar']) && !empty($izin['tanggal_masuk'])) { ?>
                                                <span class="badge bg-success">Done</span>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum terdapat perizinan yang diapproved.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="index.php?page=dashboard" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
            </div>
        </section>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?> 



