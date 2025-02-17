<?php include '../app/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php include '../app/views/layouts/navbar.php'; ?>
    <?php include '../app/views/layouts/sidebar.php'; ?>

    <div class="content-wrapper my-5">
        <!-- <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><i class="fa fa-edit"></i> Ajukan Perizinan</h1>
                    </div>
                </div>
            </div>
        </div> -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Form Pengajuan Perizinan</h3>
                            </div>
                            
                            <div class="card-body">
                                <?php if ($successMessage): ?>
                                    <div class="alert alert-success"><?= $successMessage ?></div>
                                    <?php unset($_SESSION['success']); ?>
                                <?php endif; ?>

                                <?php if ($errorMessage): ?>
                                    <div class="alert alert-danger"><?= $errorMessage ?></div>
                                    <?php unset($_SESSION['error']); ?>
                                <?php endif; ?>

                                <form action="index.php?page=ajukan_perizinan" method="POST">
                                    <!-- Nama Pengaju -->
                                    <div class="form-group">
                                        <label for="nama_pengaju">Nama Pengaju</label>
                                        <input type="text" class="form-control" id="nama_pengaju" 
                                               value="<?php echo $_SESSION['nama'] ?? 'Guest'; ?>" disabled>
                                    </div>

                                    <!-- NIP -->
                                    <div class="form-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" class="form-control" id="nip" 
                                               value="<?php echo $_SESSION['nip'] ?? '-'; ?>" disabled>
                                    </div>

                                    <!-- Jabatan -->
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan</label>
                                        <input type="text" class="form-control" id="jabatan" 
                                               value="<?php echo $_SESSION['jabatan'] ?? '-'; ?>" disabled>
                                    </div>

                                    <!-- Pilih Atasan -->
                                    <div class="form-group">
                                        <label for="atasan">Pilih Atasan</label>
                                        <select class="form-control" id="atasan" name="atasan" required>
                                            <option value="">-- Pilih Atasan --</option>
                                            <?php if (!empty($atasanList)) { ?>
                                                <?php foreach ($atasanList as $atasan) { ?>
                                                    <option value="<?php echo htmlspecialchars($atasan['id']); ?>">
                                                        <?php echo htmlspecialchars($atasan['nama']); ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <option value="">Tidak ada atasan tersedia</option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Tanggal dan Waktu Rencana Keluar -->
                                    <div class="form-group">
                                        <label for="tanggal_keluar">Tanggal & Waktu Rencana Keluar</label>
                                        <input type="datetime-local" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                                    </div>

                                    <!-- Durasi Keluar (Menit) -->
                                    <div class="form-group">
                                        <label for="durasi">Durasi Keluar (Menit)</label>
                                        <input type="number" class="form-control" id="durasi" name="durasi" required>
                                    </div>

                                    <!-- Alasan Perizinan -->
                                    <div class="form-group">
                                        <label for="alasan">Alasan Perizinan</label>
                                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-paper-plane"></i> Kirim Pengajuan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <a href="index.php?page=dashboard" class="btn btn-secondary btn-block">
                            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>
