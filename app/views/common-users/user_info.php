<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Info User</h2>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <?php if (!empty($user)): ?>
                            <dl class="row">
                                <dt class="col-sm-4 text-primary">Nama</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['user_nama']); ?></dd>

                                <dt class="col-sm-4 text-primary">NIP</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['nip']); ?></dd>

                                <dt class="col-sm-4 text-primary">Email</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['email']); ?></dd>

                                <dt class="col-sm-4 text-primary">Jabatan</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['jabatan']); ?></dd>
                            </dl>
                        <?php else: ?>
                            <div class="alert alert-warning text-center shadow-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i> Info user tidak ditemukan.
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

<?php include '../app/views/layouts/footer.php'; ?>
