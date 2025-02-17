<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary mb-4">Detail Pegawai</h2>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <?php if (!empty($user)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <tr>
                                        <th class="table-primary" style="width: 300px;">Nama</th>
                                        <td><?= htmlspecialchars($user['user_nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-primary" style="width: 300px;">NIP</th>
                                        <td><?= htmlspecialchars($user['nip']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-primary" style="width: 300px;">Email</th>
                                        <td><?= htmlspecialchars($user['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-primary" style="width: 300px;">Jabatan</th>
                                        <td><?= htmlspecialchars($user['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-primary" style="width: 300px;">Total Lama Keluar Kantor</th>
                                        <td><?= htmlspecialchars($user['total_menit_keluar']); ?> menit</td>
                                    </tr>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center shadow-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i> User tersebut belum melakukan ijin.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php?page=daftar_pegawai" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pegawai
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
<?php include '../app/views/layouts/footer.php'; ?>

<style>
    /* Efek Hover & Animasi */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
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
