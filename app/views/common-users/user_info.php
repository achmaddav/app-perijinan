<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Info Profil</h2>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
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

                        <?php if (!empty($user)): ?>
                            <dl class="row">
                                <dt class="col-sm-4 text-primary">Nama</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['user_nama']); ?></dd>

                                <dt class="col-sm-4 text-primary">NIP</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['nip']); ?></dd>

                                <dt class="col-sm-4 text-primary">Tanggal Lahir</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['birth_of_date']); ?></dd>

                                <dt class="col-sm-4 text-primary">Tempat Lahir</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['place_of_birth']); ?></dd>

                                <dt class="col-sm-4 text-primary">Alamat</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['address']); ?></dd>

                                <dt class="col-sm-4 text-primary">No. Telepon</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['phone_number']); ?></dd>

                                <dt class="col-sm-4 text-primary">Email</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['email']); ?></dd>

                                <dt class="col-sm-4 text-primary">Jabatan</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['jabatan']); ?></dd>
                            </dl>

                            <!-- Tombol aksi -->
                            <?php if ($jabatan !== 'SCT') { ?>
                                <div class="mt-3 d-flex gap-2">
                                    <a href="/app-perijinan/edit_profil"
                                        class="btn btn-primary btn-sm shadow-sm rounded">
                                        Update Profil
                                    </a>

                                    <a href="/app-perijinan/edit_password"
                                        class="btn btn-warning btn-sm shadow-sm rounded">
                                        Ubah Password
                                    </a>
                                </div>
                            <?php } ?>

                        <?php else: ?>
                            <div class="alert alert-warning text-center shadow-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i> Info user tidak ditemukan.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="/app-perijinan/dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pegawai
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Custom Styles -->
<style>
    dl {
        margin: 0;
    }

    dt {
        font-weight: 600;
        padding: 10px 0;
        border-bottom: 1px solid #eaeaea;
    }

    dd {
        margin-left: 0;
        padding: 10px 0;
        border-bottom: 1px solid #eaeaea;
    }

    .card-body {
        padding: 2rem;
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>