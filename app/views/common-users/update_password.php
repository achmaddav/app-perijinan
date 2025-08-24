<?php if (isset($_SESSION['password_updated'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var reloginModal = new bootstrap.Modal(document.getElementById('reloginModal'));
        reloginModal.show();
    });
</script>
<?php unset($_SESSION['password_updated']); endif; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid py-3">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Update Password</h4>
                    </div>

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

                        <form action="/app-perijinan/process_update_password" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">    

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-primary">Password Lama</label>
                                <div class="col-sm-9">
                                    <input type="password" 
                                    id="password_lama" 
                                    name="password_lama" 
                                    class="form-control shadow-sm" 
                                    required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-primary">Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password" 
                                    id="password_baru" 
                                    name="password_baru" 
                                    class="form-control shadow-sm" 
                                    required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-primary">Konfirmasi Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password" 
                                    id="konfirmasi_password" 
                                    name="konfirmasi_password" 
                                    class="form-control shadow-sm" 
                                    required>
                                </div>
                            </div>

                            <div class="d-flex mt-4 gap-2">
                                <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                                <a href="/app-perijinan/profil" class="btn btn-sm btn-outline-secondary shadow-sm">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                            </div>
                        </form>

                        <!-- Modal Konfirmasi Login Ulang -->
                        <div class="modal fade" id="reloginModal" tabindex="-1" aria-labelledby="reloginLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0 rounded-3">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="reloginLabel">Konfirmasi Login Ulang</h5>
                                </div>
                                <div class="modal-body">
                                    Password Anda berhasil diperbarui.<br>
                                    Silakan login ulang dengan password baru Anda.
                                </div>
                                <div class="modal-footer">
                                    <a href="/app-perijinan/logout" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Login Ulang
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>

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
