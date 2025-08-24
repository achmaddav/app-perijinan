<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid py-3">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Update Profil</h4>
                    </div>

                    <div class="card-body">
                        <?php if (!empty($user)): ?> 
                            <form action="/app-perijinan/process_update_profil" method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">    
                           
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="user_nama" class="form-control shadow-sm"
                                            value="<?= htmlspecialchars($user['user_nama']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">Tanggal Lahir</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="birth_of_date" class="form-control shadow-sm"
                                            value="<?= htmlspecialchars($user['birth_of_date']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">Tempat Lahir</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="place_of_birth" class="form-control shadow-sm"
                                            value="<?= htmlspecialchars($user['place_of_birth']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">Alamat</label>
                                    <div class="col-sm-9">
                                        <textarea name="address" class="form-control rounded-3 shadow-sm" rows="3"
                                                required><?= htmlspecialchars($user['address']); ?></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">No. Telepon</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone_number" class="form-control shadow-sm"
                                            value="<?= htmlspecialchars($user['phone_number']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label text-primary">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control shadow-sm"
                                            value="<?= htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                    <a href="/app-perijinan/profil" class="btn btn-sm btn-outline-secondary shadow-sm">
                                        <i class="fas fa-times me-2"></i> Batal
                                    </a>
                                </div>

                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning text-center shadow-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i> Data user tidak ditemukan.
                            </div>
                        <?php endif; ?>
                    </div>
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
