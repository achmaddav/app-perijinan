<?php
include __DIR__ . '/../layouts/header.php';
?>

<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Daftar Log Non Perizinan</h2>

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

                <!-- Tombol Tambah Perizinan -->
                <div class="text-end mb-3">
                    <button class="btn btn-primary shadow-sm px-3 py-2"
                        data-bs-toggle="modal" data-bs-target="#modalTambahPerizinan">
                        <i class="fas fa-plus me-2"></i> Tambah Perizinan
                    </button>
                </div>

                <!-- Card Daftar Perizinan -->
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table-perizinan" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengaju</th>
                                        <th>Verifikasi Keluar</th>
                                        <th>Verifikasi Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($nonIzinList)): ?>
                                        <?php foreach ($nonIzinList as $index => $nonIzin): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($nonIzin['nama_pengaju']); ?></td>
                                                <td><?= htmlspecialchars($nonIzin['tanggal_keluar']); ?></td>
                                                <td class="text-center">
                                                    <?php if (empty($nonIzin['tanggal_masuk'])): ?>
                                                        <form action="/app-perijinan/verify_masuk_non_perizinan" method="POST">
                                                            <input type="hidden" name="non_perizinan_id" value="<?= $nonIzin['id']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm rounded-pill shadow-sm">Verifikasi Masuk</button>
                                                        </form>
                                                    <?php elseif (!empty($nonIzin['tanggal_masuk'])): ?>
                                                        <?= htmlspecialchars($nonIzin['tanggal_masuk']); ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Kembali ke Dashboard -->
                <div class="text-center mt-4">
                    <a href="/app-perijinan/dashboard" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Tambah Perizinan -->
<div class="modal fade" id="modalTambahPerizinan" tabindex="-1" aria-labelledby="modalTambahPerizinanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalTambahPerizinanLabel">
                    <i class="fas fa-user-plus me-2 text-primary"></i> Tambah Perizinan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/app-perijinan/tambah_non_perizinan" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Pilih Karyawan</label>
                        <select name="user_id" id="userDropdown" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Karyawan --</option>
                            <?php foreach ($userList as $user): ?>
                                <option value="<?= htmlspecialchars($user['id']); ?>">
                                    <?= htmlspecialchars($user['nip'] . " : " . $user['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="datetime-local" name="waktu_keluar" id="waktu_keluar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        font-size: 0.9rem;
        /* Ukuran teks sedikit lebih kecil */
        padding: 8px 16px;
        /* Padding yang lebih pas */
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #003d82);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .btn-warning:hover,
    .btn-secondary:hover,
    .btn-outline-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .alert {
        animation: fadeIn 0.5s, fadeOut 0.5s 3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    .modal-content {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer .btn {
        transition: background 0.3s ease;
    }

    .modal-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>