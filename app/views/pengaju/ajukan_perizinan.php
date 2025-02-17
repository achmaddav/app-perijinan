<?php include '../app/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center text-primary mb-4">Ajukan Perizinan</h2>
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
                                <?php if ($successMessage): ?>
                                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> <?= $successMessage ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($errorMessage): ?>
                                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i> <?= $errorMessage ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="index.php?page=ajukan_perizinan" method="POST">
                                    <div class="mb-3">
                                        <label for="nama_pengaju" class="form-label">Nama Pengaju</label>
                                        <input type="text" class="form-control shadow-sm" id="nama_pengaju" 
                                               value="<?= $_SESSION['nama'] ?? 'Guest'; ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" class="form-control shadow-sm" id="nip" 
                                               value="<?= $_SESSION['nip'] ?? '-'; ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jabatan" class="form-label">Jabatan</label>
                                        <input type="text" class="form-control shadow-sm" id="jabatan" 
                                               value="<?= $_SESSION['jabatan'] ?? '-'; ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="atasan" class="form-label">Pilih Atasan</label>
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

                                    <div class="mb-3">
                                        <label for="tanggal_keluar" class="form-label">Tanggal & Waktu Rencana Keluar</label>
                                        <input type="datetime-local" class="form-control shadow-sm" id="tanggal_keluar" name="tanggal_keluar" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="durasi" class="form-label">Durasi Keluar (Menit)</label>
                                        <input type="number" class="form-control shadow-sm" id="durasi" name="durasi" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alasan" class="form-label">Alasan Perizinan</label>
                                        <textarea class="form-control shadow-sm" id="alasan" name="alasan" rows="3" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 shadow-sm mb-3">
                                        <i class="fa fa-paper-plane"></i> Kirim Pengajuan
                                    </button>
                                </form>

                                <div class="text-center mt-2">
                                    <a href="index.php?page=dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    /* .btn-hover {
        transition: all 0.3s ease-in-out;
    } */

    .btn-danger:hover, .btn-outline-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
</style>

<?php include '../app/views/layouts/footer.php'; ?>