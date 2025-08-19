<?php include __DIR__ . '/layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/layouts/navbar.php'; ?>
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-12 text-center">
                        <h1 class="display-4">Selamat datang, <?php echo $nama; ?></h1>
                        <p class="text-muted"><?php echo $nip; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                <?php if ($jabatan === 'STF' || $jabatan === 'KTA') { ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Ajukan Perizinan</h5>
                                <p class="card-text text-muted">Ajukan perizinan dengan mudah melalui sistem.</p>
                                <a href="/app-perijinan/ajukan_perizinan" class="btn btn-outline-primary rounded-pill">Ajukan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">Status Perizinan</h5>
                                <p class="card-text text-muted">Lihat riwayat perizinan yang telah Anda ajukan.</p>
                                <a href="/app-perijinan/status_perizinan" class="btn btn-outline-info rounded-pill">Lihat Status</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'KEP') { ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">Daftar Pegawai</h5>
                                <p class="card-text text-muted">Lihat semua data pegawai.</p>
                                <a href="/app-perijinan/daftar_pegawai" class="btn btn-outline-info rounded-pill">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'KTA' || $jabatan === 'KEP') { ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-secondary">Daftar Perizinan</h5>
                                <p class="card-text text-muted">Lihat semua perizinan karyawan yang sedang berlangsung.</p>
                                <a href="/app-perijinan/daftar_perizinan" class="btn btn-outline-secondary rounded-pill">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Laporan Perizinan</h5>
                                <p class="card-text text-muted">Lihat laporan perizinan untuk analisis.</p>
                                <a href="/app-perijinan/laporan_perizinan" class="btn btn-outline-warning rounded-pill">Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'SCT') { ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Verifikasi Keluar Masuk</h5>
                                <p class="card-text text-muted">Pastikan keabsahan perizinan masuk dan keluar.</p>
                                <a href="/app-perijinan/verifikasi" class="btn btn-outline-warning rounded-pill">Verifikasi</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Verifikasi Tidak  Berizin</h5>
                                <p class="card-text text-muted">Pastikan keabsahan perizinan masuk dan keluar.</p>
                                <a href="/app-perijinan/verifikasi_non_perizinan" class="btn btn-outline-danger rounded-pill">Verifikasi</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">Riwayat Verifikasi</h5>
                                <p class="card-text text-muted">Daftar riwayat perizinan yang sudah diverifikasi</p>
                                <a href="/app-perijinan/history_verify" class="btn btn-outline-info rounded-pill">Lihat Riwayat</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">Riwayat Tidak Berizin</h5>
                                <p class="card-text text-muted">Daftar riwayat tidak berizin</p>
                                <a href="/app-perijinan/history_verify_non_perizinan" class="btn btn-outline-info rounded-pill">Lihat Riwayat</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </section>
    </div>

</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        color: #fff;
    }
</style>

<?php include __DIR__ . '/layouts/footer.php'; ?>



