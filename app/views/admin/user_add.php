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
                                        <div class="col-md-12 mb-3">
                                            <div class="btn-group shadow-sm" role="group" aria-label="Import dan Download Excel">
                                                <!-- Tombol Import Excel -->
                                                <button type="button" 
                                                        class="btn btn-primary d-flex align-items-center py-1 px-3"
                                                        data-bs-toggle="modal" data-bs-target="#importModal">
                                                    <i class="fa fa-file-excel me-2"></i> Import
                                                </button>

                                                <!-- Tombol Unduh Format Excel -->
                                                <a href="/app-perijinan/download_format_excel" 
                                                class="btn btn-success d-flex align-items-center py-1 px-3">
                                                    <i class="fa fa-download me-2"></i> Download Format
                                                </a>
                                            </div>
                                        </div>

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
                                                                <option value="<?= htmlspecialchars($position['id']); ?>" data-kode="<?= htmlspecialchars($position['kode']); ?>">
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
                                                <label for="timker" class="col-md-4 col-form-label">Tim Kerja</label>
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

                                    <!-- Tombol Simpan -->
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

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="importForm"
                action="/app-perijinan/user_import_excel"
                method="POST"
                enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-file-excel me-2"></i> Import Pegawai dari Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Pilih File Excel (.xls)</label>
                        <input type="file" class="form-control" name="file_excel" id="file_excel" accept=".xls" required>
                    </div>
                    <small class="text-muted">
                        Format kolom harus sesuai:
                        <b>NIP, Nama, Tanggal Lahir, Tempat Lahir, Email, No Telp, Alamat, Jabatan, Tim Kerja, Ketua Tim, Kepala Balai, Tanggal Kerja</b>.
                        <br>
                        <b>Catatan : format tanggal (YYYY-MM-DD).</b>
                    </small>
                    
                    <!-- Hasil Import -->
                    <div class="card import-result-card mt-4" id="importResultCard">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="importResultTitle">Import Berhasil</span>
                            </h5>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="stats-box text-center">
                                        <div class="stat-number success-stat" id="successCount">0</div>
                                        <div>Data Berhasil</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="stats-box text-center">
                                        <div class="stat-number error-stat" id="errorCount">0</div>
                                        <div>Data Gagal</div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3" id="importResultMessage"></p>
                            <div id="errorList" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnImportSubmit" class="btn btn-primary">Import</button>
                </div>
            </form>

            <!-- Overlay dengan Progress Bar -->
            <div id="loadingOverlay">
                <h4 class="text-primary mb-4">Sedang mengimport data...</h4>
                
                <div class="progress-info">
                    <span>Status:</span>
                    <span id="progressPercentage">0%</span>
                </div>
                
                <div class="progress">
                    <div id="importProgress" class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="import-status">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="statusText">Memulai proses import...</span>
                </div>
                
                <div class="import-status">
                    <i class="fas fa-clock me-2"></i>
                    <span id="timeElapsed">Waktu: 0 detik</span>
                </div>
                
                <div class="mt-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                
                <button id="cancelButton" class="btn btn-outline-danger mt-4">
                    <i class="fas fa-times-circle me-2"></i>Batalkan Import
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    .btn-group .btn {
        transition: all 0.2s ease-in-out;
        font-size: 0.85rem; /* lebih kecil */
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4a90e2, #357abd);
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
    }

    .btn-danger:hover,
    .btn-outline-primary:hover,
    .btn-outline-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    #loadingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 1055;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .modal-content {
        position: relative;
    }
    .progress {
        height: 25px;
        width: 80%;
        margin: 20px auto;
        border-radius: 20px;
    }
    .progress-bar {
        transition: width 0.5s ease-in-out;
    }
    .progress-info {
        display: flex;
        justify-content: space-between;
        width: 80%;
        margin-bottom: 10px;
    }
    .import-status {
        margin-top: 15px;
        font-size: 14px;
        color: #495057;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    .success-animation {
        animation: successPulse 2s ease-in-out;
    }
    @keyframes successPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .import-result-card {
        display: none;
        margin-top: 20px;
        border-left: 5px solid #28a745 !important;
    }
    .stats-box {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin: 10px 0;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
    }
    .success-stat {
        color: #28a745;
    }
    .error-stat {
        color: #dc3545;
    }
</style>

<script src="/app-perijinan/assets/js/admin/user_add.js?v=<?= time(); ?>"></script>
<?php include  __DIR__ . '/../layouts/footer.php'; ?>