<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content py-3">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-header bg-primary text-white">
                                <h4 class="card-title mb-0">Form Pengajuan Cuti</h4>
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

                                <form action="/app-perijinan/insert_cuti" method="POST">
                                    <div class="row">
                                        <!-- KOLUM KIRI -->
                                        <div class="col-md-6">

                                            <div class="row mb-3 align-items-center">
                                                <label for="sisa_cuti" class="col-md-4 col-form-label">Saldo Cuti</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="sisa_cuti"
                                                        value="<?= htmlspecialchars($sisaCuti) . ' hari'; ?>" disabled>
                                                </div>
                                            </div>

                                            
                                                <div class="row mb-3 align-items-center">
                                                    <label for="atasan" class="col-md-4 col-form-label">Kepala Balai</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control shadow-sm" id="nama_atasan"
                                                            value="<?= htmlspecialchars($kepala_balai['nama']); ?>" disabled>
                                                        <input type="hidden" name="atasan" id="atasan" value="<?= htmlspecialchars($kepala_balai['id']); ?>">
                                                    </div>
                                                </div>

                                            <?php if ($jabatan === 'STF') { ?>
                                                <div class="row mb-3 align-items-center">
                                                    <label for="ketua" class="col-md-4 col-form-label">Ketua Tim</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control shadow-sm" id="nama_ketua_tim"
                                                            value="<?= htmlspecialchars($ketua_tim['nama']); ?>" disabled>
                                                        <input type="hidden" name="ketua" id="ketua" value="<?= htmlspecialchars($ketua_tim['id']); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="row mb-3 align-items-center">
                                                <label for="nama_pengaju" class="col-md-4 col-form-label">Nama Pemohon</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="nama_pengaju"
                                                        value="<?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest'; ?>" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="nip" class="col-md-4 col-form-label">NIP</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="nip"
                                                        value="<?= isset($_SESSION['nip']) ? $_SESSION['nip'] : '-'; ?>" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="jabatan" class="col-md-4 col-form-label">Jabatan</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="jabatan"
                                                        value="<?= htmlspecialchars($jabatan_user['nama'] . ' ' . $_SESSION['divisi']) ?>" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="unit_kerja" class="col-md-4 col-form-label">Unit Kerja</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="unit_kerja"
                                                        value="BPTU-HPT Sembawa" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="masa_kerja" class="col-md-4 col-form-label">Masa Kerja</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="masa_kerja"
                                                        value="<?= $_SESSION['masa_kerja'] ?? '-'; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KOLUM KANAN -->
                                        <div class="col-md-6">

                                            <div class="row mb-3 align-items-center">
                                                <label for="tipeCuti" class="col-md-4 col-form-label">Jenis Cuti</label>
                                                <div class="col-md-8">
                                                    <select class="form-select shadow-sm" id="tipeCuti" name="tipeCuti" required>
                                                        <option value="" disabled selected>-- Pilih Jenis Cuti --</option>
                                                        <?php if (!empty($tipeCutiList)) { ?>
                                                            <?php foreach ($tipeCutiList as $tipeCuti) { ?>
                                                                <option value="<?= htmlspecialchars($tipeCuti['id']); ?>">
                                                                    <?= htmlspecialchars($tipeCuti['nama']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">Tipe cuti tidak tersedia</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="dari_tanggal" class="col-md-4 col-form-label">Dari Tanggal</label>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control shadow-sm" id="dari_tanggal" name="dari_tanggal" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="sampai_tanggal" class="col-md-4 col-form-label">Sampai Tanggal</label>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control shadow-sm" id="sampai_tanggal" name="sampai_tanggal" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="alamat" class="col-md-4 col-form-label">Alamat Cuti</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control shadow-sm" id="alamat" name="alamat" rows="3" required></textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="deskripsi" class="col-md-4 col-form-label">Deskripsi</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control shadow-sm" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                        <button type="submit" class="btn btn-outline-success rounded-pill shadow-sm mb-3 mb-md-0 me-md-2 w-100">
                                            <i class="fa fa-paper-plane me-2"></i> Kirim Pengajuan
                                        </button>
                                        <a href="/app-perijinan/dashboard_cuti" class="btn btn-outline-primary rounded-pill shadow-sm w-100">
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
    .btn-danger:hover,
    .btn-outline-primary:hover,
    .btn-outline-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
</style>

<?php include  __DIR__ . '/../layouts/footer.php'; ?>