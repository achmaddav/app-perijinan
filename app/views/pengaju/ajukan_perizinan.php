<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Ajukan Perizinan</h2>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-header bg-primary text-white">
                                <h4 class="card-title mb-0">Form Pengajuan Perizinan</h4>
                            </div>

                            <div class="card-body">
                                <!-- Notifikasi -->
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['success']); ?>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['error']); ?>
                                <?php endif; ?>

                                <form action="/app-perijinan/ajukan_perizinan" method="POST">
                                    <div class="row mb-3">
                                        <label for="nama_pengaju" class="col-md-4 col-form-label">Nama Pengaju</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control shadow-sm" id="nama_pengaju" 
                                                   value="<?= $_SESSION['nama'] ?? 'Guest'; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nip" class="col-md-4 col-form-label">NIP</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control shadow-sm" id="nip" 
                                                   value="<?= $_SESSION['nip'] ?? '-'; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="jabatan" class="col-md-4 col-form-label">Jabatan</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control shadow-sm" id="jabatan" 
                                                   value="<?= $_SESSION['jabatan'] ?? '-'; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="atasan" class="col-md-4 col-form-label">Pilih Atasan</label>
                                        <div class="col-md-8">
                                            <select class="form-select shadow-sm" id="atasan" name="atasan" required>
                                                <option value="">-- Pilih Atasan --</option>
                                                <?php if (!empty($atasanList)) { ?>
                                                    <?php foreach ($atasanList as $atasan) { ?>
                                                        <option value="<?= htmlspecialchars($atasan['id']); ?>">
                                                            <?= htmlspecialchars($atasan['nama']); ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <option value="">Tidak ada atasan tersedia</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tanggal_keluar" class="col-md-4 col-form-label">Tanggal & Waktu Keluar</label>
                                        <div class="col-md-8">
                                            <input type="datetime-local" class="form-control shadow-sm" id="tanggal_keluar" name="tanggal_keluar" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="durasi" class="col-md-4 col-form-label">Durasi Keluar (Menit)</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control shadow-sm" id="durasi" name="durasi" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="alasan" class="col-md-4 col-form-label">Alasan Perizinan</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control shadow-sm" id="alasan" name="alasan" rows="3" required></textarea>
                                        </div>
                                    </div>

                                    <!-- Tombol Kirim Pengajuan & Kembali ke Dashboard dalam satu baris -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <button type="submit" class="btn btn-outline-success rounded-pill shadow-sm mb-3 mb-md-0 me-md-2 w-100">
                                            <i class="fa fa-paper-plane me-2"></i> Kirim Pengajuan
                                        </button>
                                        <a href="/app-perijinan/dashboard" class="btn btn-outline-primary rounded-pill shadow-sm w-100">
                                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .btn-danger:hover, .btn-outline-primary:hover, .btn-outline-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
</style>

<?php include  __DIR__ . '/../layouts/footer.php'; ?>
