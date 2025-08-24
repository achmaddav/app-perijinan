<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content py-3">
            <div class="container-fluid">
                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Card Daftar Pegawai -->
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header d-flex align-items-center bg-primary text-white px-3">
                        <h5 class="mb-0">Daftar Pegawai</h5>
                        <?php if ($jabatan === 'ADM') { ?>
                            <a href="/app-perijinan/user_add" class="btn btn-success btn-sm ms-auto">
                                <i class="fas fa-plus me-1"></i> Tambah Pegawai
                            </a>
                        <?php } ?>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table-perizinan" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
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
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <!-- Tombol Detail -->
                                                        <form action="/app-perijinan/user_detail" method="POST">
                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                                            <button type="submit" name="detail" class="btn btn-info btn-sm rounded shadow-sm">
                                                                <i class="fas fa-user-circle me-1"></i> Detail
                                                            </button>
                                                        </form>

                                                        <!-- Tombol Edit -->
                                                        <?php if ($jabatan === 'ADM') { ?>
                                                            <form action="/app-perijinan/user_edit" method="POST">
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                                                <button type="submit" name="edit" class="btn btn-warning btn-sm rounded shadow-sm">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </button>
                                                            </form>
                                                        <?php } ?>
                                                    </div>
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

<style>
    .card-header {
        padding-right: 0.75rem !important;
        /* biar ada jarak kecil */
    }

    .card-header .btn {
        margin-right: 0 !important;
        /* hapus margin default tombol */
    }

    /* Efek Hover & Animasi */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .btn-info:hover,
    .btn-outline-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .alert {
        animation: fadeIn 0.5s, fadeOut 0.5s 3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>