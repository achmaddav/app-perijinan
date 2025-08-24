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
                                <h4 class="card-title mb-0">Update Pegawai</h4>
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

                                <form action="/app-perijinan/process_user_update" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">

                                    <div class="row">
                                        <!-- KIRI -->
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Nama</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="nama" class="form-control"
                                                        value="<?= htmlspecialchars($user['nama']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Tanggal Lahir</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="tanggal_lahir" class="form-control"
                                                        value="<?= htmlspecialchars($user['birth_of_date']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Tempat Lahir</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="tempat_lahir" class="form-control"
                                                        value="<?= htmlspecialchars($user['place_of_birth']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Email</label>
                                                <div class="col-md-8">
                                                    <input type="email" name="email" class="form-control"
                                                        value="<?= htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">No. Telepon</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="phone_number" class="form-control"
                                                        value="<?= htmlspecialchars($user['phone_number']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Alamat</label>
                                                <div class="col-md-8">
                                                    <textarea name="alamat" class="form-control"><?= htmlspecialchars($user['address']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KANAN -->
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">NIP</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="nip" class="form-control"
                                                        value="<?= htmlspecialchars($user['nip']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Jabatan</label>
                                                <div class="col-md-8">
                                                    <select name="jenisJabatan" class="form-select">
                                                        <option value="">-- Tidak Ada --</option>
                                                        <?php foreach ($positions as $pos): ?>
                                                            <option value="<?= $pos['id']; ?>" <?= $user['jabatan_id']==$pos['id'] ? 'selected' : ''; ?>>
                                                                <?= htmlspecialchars($pos['nama']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Tim Kerja</label>
                                                <div class="col-md-8">
                                                    <select name="timKerja" class="form-select">
                                                        <option value="">-- Tidak Ada --</option>
                                                        <?php foreach ($divisions as $div): ?>
                                                            <option value="<?= $div['id']; ?>" <?= $user['divisi_id']==$div['id'] ? 'selected' : ''; ?>>
                                                                <?= htmlspecialchars($div['nama']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Ketua Tim</label>
                                                <div class="col-md-8">
                                                    <select name="ketua_timker" class="form-select">
                                                        <option value="">-- Tidak Ada --</option>
                                                        <?php foreach ($ketua_tim_list as $kt): ?>
                                                            <option value="<?= $kt['id']; ?>" <?= $user['atasan_id']==$kt['id'] ? 'selected' : ''; ?>>
                                                                <?= htmlspecialchars($kt['nama']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Kepala Balai</label>
                                                <div class="col-md-8">
                                                    <select name="kepala_balai" class="form-select">
                                                        <option value="">-- Tidak Ada --</option>
                                                        <?php foreach ($head_office_list as $ho): ?>
                                                            <option value="<?= $ho['id']; ?>" <?= $user['kepala_id']==$ho['id'] ? 'selected' : ''; ?>>
                                                                <?= htmlspecialchars($ho['nama']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-4 col-form-label">Tanggal Kerja</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="tanggal_kerja" class="form-control"
                                                        value="<?= htmlspecialchars($user['tanggal_mulai_kerja']); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BUTTON -->
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fa fa-save me-2"></i> Update
                                        </button>
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