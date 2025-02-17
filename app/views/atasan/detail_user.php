<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Detail Pegawai</h2>

                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($user)): ?>
                            <table class="table table-bordered">
                                <!-- <tr>
                                    <th>ID</th>
                                    <td><?= htmlspecialchars($user['id']); ?></td>
                                </tr> -->
                                <tr>
                                    <th style="width: 500px;">Nama</th>
                                    <td><?= htmlspecialchars($user['user_nama']); ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 500px;">NIP</th>
                                    <td><?= htmlspecialchars($user['nip']); ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 500px;">Email</th>
                                    <td><?= htmlspecialchars($user['email']); ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 500px;">Jabatan</th>
                                    <td><?= htmlspecialchars($user['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 500px;">Total lama keluar kantor</th>
                                    <td><?= htmlspecialchars($user['total_menit_keluar']); ?> menit</td>
                                </tr>
                            </table>
                        <?php else: ?>
                            <p>User tersebut belum melakukan ijin.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="index.php?page=daftar_pegawai" class="btn btn-primary mt-3">Kembali ke Daftar User</a>
            </div>
        </section>
    </div>
</div>
<?php include '../app/views/layouts/footer.php'; ?>
