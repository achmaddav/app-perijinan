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
                                <h4 class="card-title mb-0">Tambah Pegawai</h4>
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

                                <form action="/app-perijinan/insert_user" method="POST">
                                    <div class="row">
                                        <!-- KOLUM KIRI -->
                                        <div class="col-md-6">

                                            <div class="row mb-3 align-items-center">
                                                <label for="nama" class="col-md-4 col-form-label">Nama</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="nama" name="nama" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="tanggal_lahir" class="col-md-4 col-form-label">Tanggal Lahir</label>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control shadow-sm" 
                                                        id="tanggal_lahir" name="tanggal_lahir" 
                                                        value="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="tempat_lahir" class="col-md-4 col-form-label">Tempat Lahir</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="tempat_lahir" name="tempat_lahir" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="email" class="col-md-4 col-form-label">Email</label>
                                                <div class="col-md-8">
                                                    <input type="email" class="form-control shadow-sm" id="email" name="email" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="phone_number" class="col-md-4 col-form-label">No. Telepon</label>
                                                <div class="col-md-8">
                                                    <input type="tel" class="form-control shadow-sm" id="phone_number" name="phone_number" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="alamat" class="col-md-4 col-form-label">Alamat</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control shadow-sm" id="alamat" name="alamat" rows="3" required></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KOLUM KANAN -->
                                        <div class="col-md-6">
                                            <div class="row mb-3 align-items-center">
                                                <label for="nip" class="col-md-4 col-form-label">NIP</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control shadow-sm" id="nip" name="nip" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3 align-items-center">
                                                <label for="jabatan" class="col-md-4 col-form-label">Jabatan</label>
                                                <div class="col-md-8">
                                                    <select class="form-select shadow-sm" id="jenisJabatan" name="jenisJabatan" required>
                                                        <option value="" disabled selected>-- Pilih Jabatan --</option>
                                                        <?php if (!empty($positions)) { ?>
                                                            <?php foreach ($positions as $position) { ?>
                                                                <option value="<?= htmlspecialchars($position['id']); ?>">
                                                                    <?= htmlspecialchars($position['nama']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">Pilihan jabatan tidak tersedia</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="jabatan" class="col-md-4 col-form-label">Tim Kerja</label>
                                                <div class="col-md-8">
                                                    <select class="form-select shadow-sm" id="timKerja" name="timKerja">
                                                        <option value="" disabled selected>-- Pilih Tim Kerja --</option>
                                                        <?php if (!empty($divisions)) { ?>
                                                            <?php foreach ($divisions as $division) { ?>
                                                                <option value="<?= htmlspecialchars($division['id']); ?>">
                                                                    <?= htmlspecialchars($division['nama']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">Pilihan tim kerja tidak tersedia</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="jabatan" class="col-md-4 col-form-label">Ketua Tim Kerja</label>
                                                <div class="col-md-8">
                                                    <select class="form-select shadow-sm" id="ketua_timker" name="ketua_timker">
                                                        <option value="" disabled selected>-- Pilih Ketua Tim Kerja --</option>
                                                        <?php if (!empty($ketua_tim_list)) { ?>
                                                            <?php foreach ($ketua_tim_list as $ketua_tim) { ?>
                                                                <option value="<?= htmlspecialchars($ketua_tim['id']); ?>">
                                                                    <?= htmlspecialchars($ketua_tim['nama']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">Pilihan ketua tim kerja tidak tersedia</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="jabatan" class="col-md-4 col-form-label">Kepala Balai</label>
                                                <div class="col-md-8">
                                                    <select class="form-select shadow-sm" id="kepala_balai" name="kepala_balai">
                                                        <option value="" disabled selected>-- Pilih Kepalai Balai --</option>
                                                        <?php if (!empty($head_office_list)) { ?>
                                                            <?php foreach ($head_office_list as $head_office) { ?>
                                                                <option value="<?= htmlspecialchars($head_office['id']); ?>">
                                                                    <?= htmlspecialchars($head_office['nama']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">Pilihan kepala balai tidak tersedia</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="tanggal_kerja" class="col-md-4 col-form-label">Tanggal Kerja</label>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control shadow-sm" 
                                                        id="tanggal_kerja" name="tanggal_kerja" 
                                                        value="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                        <button type="submit" class="btn btn-outline-success rounded-pill shadow-sm mb-3 mb-md-0 me-md-2 w-100">
                                            <i class="fa fa-save me-2"></i> Simpan
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