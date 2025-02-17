<?php include '../app/views/layouts/header.php'; ?>
<div class="wrapper">

    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Selamat datang <?php echo $nama; ?> - <?php echo $nip; ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                <?php if ($jabatan === 'User' || $jabatan === 'Atasan') { ?>
                    <!-- Blok untuk Ajukan Perizinan dan Riwayat Perizinan (untuk User & Atasan) -->
                    <div class="col-lg-6">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Ajukan Perizinan</h5>
                                <p class="card-text">Ajukan perizinan dengan mudah melalui sistem.</p>
                                <a href="index.php?page=ajukan_perizinan" class="btn btn-light">Ajukan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Riwayat Perizinan</h5>
                                <p class="card-text">Lihat riwayat perizinan yang telah Anda ajukan.</p>
                                <a href="index.php?page=riwayat_perizinan" class="btn btn-light">Lihat Riwayat</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'Atasan' || $jabatan === 'SuperUser') { ?>
                    <!-- Blok untuk Daftar Perizinan dan Laporan Perizinan (untuk Atasan & SuperUser) -->
                    <div class="col-lg-6">
                        <div class="card bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Daftar Pegawai</h5>
                                <p class="card-text">Lihat semua data pegawai.</p>
                                <a href="index.php?page=daftar_pegawai" class="btn btn-light">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title">Daftar Perizinan</h5>
                                <p class="card-text">Lihat semua perizinan karyawan yang sedang berlangsung.</p>
                                <a href="index.php?page=daftar_perizinan" class="btn btn-light">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card bg-warning">
                            <div class="card-body">
                                <h5 class="card-title">Laporan Perizinan</h5>
                                <p class="card-text">Lihat laporan perizinan untuk analisis.</p>
                                <a href="index.php?page=laporan_perizinan" class="btn btn-light">Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'Satpam') { ?>
                    <!-- Blok untuk Satpam -->
                    <div class="col-lg-6">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Verifikasi Keluar Masuk</h5>
                                <p class="card-text">Pastikan keabsahan perizinan masuk dan keluar.</p>
                                <a href="index.php?page=verifikasi" class="btn btn-light">Verifikasi</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </section>
    </div>

</div>

<?php include '../app/views/layouts/footer.php'; ?>
