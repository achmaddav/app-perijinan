<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">
                    <?= isset($data_jabatan) ? "Edit Jabatan" : "Tambah Jabatan" ?>
                </h2>

                <!-- Notifikasi -->
                <?php if (isset($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3 mx-auto" style="max-width:500px;">
                    <div class="card-body">
                        <form method="post" class="mx-auto" style="max-width:400px;">
                            <div class="mb-3">
                                <label class="form-label">Kode Jabatan</label>
                                <input type="text" name="kode" class="form-control" required
                                    value="<?= $data_jabatan['kode'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Jabatan</label>
                                <input type="text" name="nama" class="form-control" required
                                    value="<?= $data_jabatan['nama'] ?? '' ?>">
                            </div>
                            <button type="submit" class="btn btn-primary shadow-sm rounded-3">
                                <i class="fas fa-save me-2"></i> <?= isset($data_jabatan) ? "Update" : "Simpan" ?>
                            </button>
                            <a href="index.php?page=index_jabatan" class="btn btn-outline-secondary shadow-sm rounded-3 ms-2">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../layouts/footer.php'; ?>

