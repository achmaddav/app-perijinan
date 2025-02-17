<?php include '../app/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Daftar Pegawai</h2>

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
                                    <th>NIP</th>
                                    <th>Email</th>
                                    <th>Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($user['nama']); ?></td>
                                            <td><?= htmlspecialchars($user['nip']); ?></td>
                                            <td><?= htmlspecialchars($user['email']); ?></td>
                                            <td><?= htmlspecialchars($user['jabatan']); ?></td>
                                            <td class="text-center">
                                                <form action="index.php?page=user_detail" method="POST">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                                    <button type="submit" name="detail" class="btn btn-info btn-sm">Detail</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data user yang ditampilkan.</td>
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

