<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link text-decoration-none">
        <span class="brand-text font-weight-light">Aplikasi Perizinan</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Menu untuk User dan Atasan (Ajukan & Riwayat Perizinan) -->
                <?php if ($jabatan === 'User' || $jabatan === 'Atasan') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=ajukan_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>Ajukan Perizinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=riwayat_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Perizinan</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk SuperUser (Daftar Pegawai) -->
                <?php if ($jabatan === 'SuperUser') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=daftar_pegawai" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Daftar Pegawai</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk Atasan dan SuperUser (Daftar & Laporan Perizinan) -->
                <?php if ($jabatan === 'Atasan' || $jabatan === 'SuperUser') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=daftar_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Daftar Perizinan</p>
                        </a>
                    </li>
                    <!-- Jika perlu menu Persetujuan Perizinan, cukup hapus komentar di bawah ini -->
                    <!--
                    <li class="nav-item">
                        <a href="index.php?page=persetujuan_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-check"></i>
                            <p>Persetujuan Perizinan</p>
                        </a>
                    </li>
                    -->
                    <li class="nav-item">
                        <a href="index.php?page=laporan_perizinan" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Perizinan</p>
                        </a>
                    </li>
                <?php } ?>

                <!-- Menu untuk Satpam -->
                <?php if ($jabatan === 'Satpam') { ?>
                    <li class="nav-item">
                        <a href="index.php?page=verifikasi" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Verifikasi Keluar Masuk</p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a href="index.php?page=logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
