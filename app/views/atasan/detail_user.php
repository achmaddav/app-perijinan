<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center text-primary py-3">Detail Pegawai</h2>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <?php if (!empty($user)): ?>
                            <dl class="row">
                                <dt class="col-sm-4 text-primary">Nama</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['user_nama']); ?></dd>

                                <dt class="col-sm-4 text-primary">NIP</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['nip']); ?></dd>

                                <dt class="col-sm-4 text-primary">Email</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['email']); ?></dd>

                                <dt class="col-sm-4 text-primary">Jabatan</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($user['jabatan']); ?></dd>

                                <dt class="col-sm-4 text-primary">Masa Kerja</dt>
                                <dd class="col-sm-8">
                                    <?= htmlspecialchars($user['tahun_masa_kerja']); ?>
                                    <?= htmlspecialchars($user['bulan_masa_kerja']); ?>
                                </dd>

                                <dt class="col-sm-4 text-primary">Total Lama Keluar Kantor</dt>
                                <dd class="col-sm-8">
                                    <?= htmlspecialchars($user['total_waktu_keluar']); ?>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalRiwayat"
                                        class="text-info ms-2" data-bs-toggle="tooltip" title="Lihat rincian">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </dd>
                                <dt class="col-sm-4 text-primary">Total Lama Keluar Kantor Tidak Berizin</dt>
                                <dd class="col-sm-8">
                                    <?= htmlspecialchars($user['total_waktu_keluar_non_berizin']); ?>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalRiwayatNonBerizin"
                                        class="text-info ms-2" data-bs-toggle="tooltip" title="Lihat rincian">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </dd>
                            </dl>
                        <?php else: ?>
                            <div class="alert alert-warning text-center shadow-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i> User tersebut belum melakukan ijin.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="/app-perijinan/daftar_pegawai" class="btn btn-outline-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pegawai
                    </a>
                </div>

                <!-- Modal Riwayat Perizinan -->
                <div class="modal fade" id="modalRiwayat" tabindex="-1" aria-labelledby="modalRiwayatLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-primary" id="modalRiwayatLabel">Rincian Perizinan <?= htmlspecialchars($user['user_nama']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="align-middle">No</th>
                                            <th class="align-middle">Tanggal</th>
                                            <th class="align-middle">Jam Keluar</th>
                                            <th class="align-middle">Jam Kembali</th>
                                            <th class="align-middle">Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rincian)): ?>
                                            <?php foreach ($rincian as $index => $izin): ?>
                                                <tr>
                                                    <td class="align-middle"><?= $index + 1; ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($izin['tanggal']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($izin['jam_keluar']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($izin['jam_kembali']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($izin['durasi']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center align-middle text-muted">Tidak ada rincian perizinan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Riwayat Tidak Berizin -->
                <div class="modal fade" id="modalRiwayatNonBerizin" tabindex="-1" aria-labelledby="modalRiwayatLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-primary" id="modalRiwayatLabel">Rincian Perizinan <?= htmlspecialchars($user['user_nama']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="align-middle">No</th>
                                            <th class="align-middle">Tanggal</th>
                                            <th class="align-middle">Jam Keluar</th>
                                            <th class="align-middle">Jam Kembali</th>
                                            <th class="align-middle">Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rincianNonBerizin)): ?>
                                            <?php foreach ($rincianNonBerizin as $index => $nonBerizin): ?>
                                                <tr>
                                                    <td class="align-middle"><?= $index + 1; ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($nonBerizin['tanggal']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($nonBerizin['jam_keluar']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($nonBerizin['jam_kembali']); ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($nonBerizin['durasi']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center align-middle text-muted">Tidak ada rincian perizinan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<!-- Custom Styles -->
<style>
    dl {
        margin: 0;
    }

    dt {
        font-weight: 600;
        padding: 10px 0;
        border-bottom: 1px solid #eaeaea;
    }

    dd {
        margin-left: 0;
        padding: 10px 0;
        border-bottom: 1px solid #eaeaea;
    }

    .card-body {
        padding: 2rem;
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>