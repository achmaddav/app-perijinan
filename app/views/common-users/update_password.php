<?php if (isset($_SESSION['password_updated'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var reloginModal = new bootstrap.Modal(document.getElementById('reloginModal'));
            reloginModal.show();
        });
    </script>
<?php unset($_SESSION['password_updated']);
endif; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid py-3 d-flex justify-content-center">
                <div class="card shadow border-0" style="max-width: 600px; width: 100%;">
                    <div class="card-header bg-gradient-primary text-white">
                        <h4 class="card-title mb-0"><i class="fas fa-lock me-2"></i> Update Password</h4>
                    </div>

                    <div class="card-body p-4">
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

                            <!-- Password Lama -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">Password Lama</label>
                                <div class="input-group">
                                    <input type="password"
                                        id="password_lama"
                                        name="password_lama"
                                        class="form-control shadow-sm"
                                        placeholder="Masukkan password lama"
                                        required>
                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_lama', this)">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">Password Baru</label>
                                <div class="input-group">
                                    <input type="password"
                                        id="password_baru"
                                        name="password_baru"
                                        class="form-control shadow-sm"
                                        placeholder="Masukkan password baru"
                                        required>
                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_baru', this)">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                <!-- Indikator -->
                                <div class="mt-2">
                                    <div class="progress" style="height: 6px;">
                                        <div id="passwordStrengthBar" class="progress-bar" role="progressbar"></div>
                                    </div>
                                    <small id="passwordStrengthText" class="text-muted">Belum diisi</small>
                                </div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password"
                                        id="konfirmasi_password"
                                        name="konfirmasi_password"
                                        class="form-control shadow-sm"
                                        placeholder="Ulangi password baru"
                                        required>
                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('konfirmasi_password', this)">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/app-perijinan/profil" class="btn btn-light border shadow-sm px-4">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary shadow-sm px-4">
                                    <i class="fas fa-save me-2"></i> Simpan
                                </button>
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

    // Cek kekuatan password
    document.getElementById("password_baru").addEventListener("input", function () {
        const val = this.value;
        const bar = document.getElementById("passwordStrengthBar");
        const text = document.getElementById("passwordStrengthText");

        let strength = 0;
        if (val.length >= 6) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;
        if (val.length >= 12) strength++;

        let percentage = (strength / 5) * 100;
        bar.style.width = percentage + "%";
        bar.style.transition = "width 0.3s ease"; // animasi smooth

        if (!val) {
            bar.className = "progress-bar bg-secondary";
            bar.style.width = "0%";
            text.textContent = "Belum diisi";
            text.className = "text-muted";
        } else if (strength <= 1) {
            bar.className = "progress-bar bg-danger";
            text.textContent = "Sangat Lemah";
            text.className = "text-danger";
        } else if (strength == 2) {
            bar.className = "progress-bar bg-warning";
            text.textContent = "Lemah";
            text.className = "text-warning";
        } else if (strength == 3) {
            bar.className = "progress-bar bg-info";
            text.textContent = "Sedang";
            text.className = "text-info";
        } else {
            bar.className = "progress-bar bg-success";
            text.textContent = "Kuat";
            text.className = "text-success";
        }
    });

</script>