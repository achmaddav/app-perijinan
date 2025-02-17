<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Sidebar -->
<aside class="main-sidebar bg-white shadow-sm rounded-4">
    <a href="#" class="brand-link text-decoration-none text-center d-block border-bottom">
        <span class="brand-text font-weight-bold text-primary">Aplikasi Perizinan</span>
    </a>
    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link text-dark rounded-3">
                        <i class="nav-icon fas fa-home text-primary"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Menu untuk User dan Atasan (Ajukan & Riwayat Perizinan) -->
                <?php if ($jabatan === 'User' || $jabatan === 'Atasan') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=ajukan_perizinan" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-edit text-success"></i>
                            <p>Ajukan Perizinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=riwayat_perizinan" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-history text-info"></i>
                            <p>Riwayat Perizinan</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk SuperUser (Daftar Pegawai) -->
                <?php if ($jabatan === 'SuperUser') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=daftar_pegawai" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-user text-warning"></i>
                            <p>Daftar Pegawai</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk Atasan dan SuperUser (Daftar & Laporan Perizinan) -->
                <?php if ($jabatan === 'Atasan' || $jabatan === 'SuperUser') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=daftar_perizinan" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-list text-secondary"></i>
                            <p>Daftar Perizinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=laporan_perizinan" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-file-alt text-danger"></i>
                            <p>Laporan Perizinan</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk Satpam -->
                <?php if ($jabatan === 'Satpam') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=verifikasi" class="nav-link text-dark rounded-3">
                            <i class="nav-icon fas fa-user-check text-danger"></i>
                            <p>Verifikasi Keluar Masuk</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Logout dengan Popup Konfirmasi -->
                <li class="nav-item">
                    <a href="#" class="nav-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="index.php?page=logout" class="btn btn-danger">Ya, Logout</a>
            </div>
        </div>
    </div>
</div>

<style>
    .main-sidebar {
        transition: all 0.3s ease;
    }

    .nav-link {
        transition: background 0.3s, color 0.3s;
        padding: 10px 15px;
        margin: 5px 0;
    }

    .nav-link:hover {
        background: #f1f5f9;
        color: #1d4ed8;
        transform: translateX(5px);
    }

    .nav-icon {
        margin-right: 8px;
    }

    .brand-link {
        background: #f8fafc;
        color: #1d4ed8;
        font-size: 1.2em;
        font-weight: bold;
        border-bottom: 1px solid #e5e7eb;
    }

    .nav-item .active {
        background: #e2e8f0;
        border-left: 4px solid #3b82f6;
        font-weight: bold;
        color: #1d4ed8;
    }

    .shadow-sm {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

    .nav-link.text-danger:hover {
        color: #dc3545;
        transform: translateX(3px);
        transition: all 0.3s ease;
    }
</style>