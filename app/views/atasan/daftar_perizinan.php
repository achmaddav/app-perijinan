<?php include '../app/views/layouts/header.php'; ?>

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
                                            <td class="text-center"><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($izin['nama_pengaju']); ?></td>
                                            <td><?= htmlspecialchars($izin['alasan']); ?></td>
                                            <td>
                                                <span class="badge bg-warning"><?= htmlspecialchars($izin['status']); ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($izin['created_at']); ?></td>
                                            <td class="text-center">
                                                <form action="index.php?page=proses_perizinan" method="POST">
                                                    <input type="hidden" name="id" value="<?= $izin['id']; ?>">
                                                    <button type="submit" name="status" value="Disetujui" class="btn btn-success btn-sm">Setujui</button>
                                                    <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada perizinan yang menunggu persetujuan.</td>
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