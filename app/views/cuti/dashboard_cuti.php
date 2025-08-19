<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-12 text-center">
                        <h1 class="display-4">Dashboard pengajuan cuti, <?php echo $nama; ?></h1>
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
                                <h5 class="card-title text-primary">Ajukan Cuti</h5>
                                <p class="card-text text-muted">Ajukan cuti dengan mudah melalui sistem.</p>
                                <a href="/app-perijinan/ajukan_cuti" class="btn btn-outline-primary rounded-pill">Ajukan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">Riwayat & Status Cuti</h5>
                                <p class="card-text text-muted">Lihat riwayat cuti yang telah Anda ajukan.</p>
                                <a href="/app-perijinan/leave_history" class="btn btn-outline-info rounded-pill">Lihat Riwayat</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($jabatan === 'KEP' || $jabatan === 'KTA') { ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Daftar Pengajuan Cuti</h5>
                                <p class="card-text text-muted">Lihat daftar pengajuan cuti pegawai.</p>
                                <a href="/app-perijinan/daftar_pengajuan_cuti" class="btn btn-outline-primary rounded-pill">Lihat Daftar</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Laporan Cuti Pegawai</h5>
                                <p class="card-text text-muted">Lihat laporan cuti pegawai.</p>
                                <a href="/app-perijinan/laporan_cuti" class="btn btn-outline-warning rounded-pill">Lihat Laporan</a>
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>



