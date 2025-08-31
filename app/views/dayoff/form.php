<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">
                    <?= isset($dayoff) ? "Edit Tanggal Merah" : "Tambah Tanggal Merah" ?>
                </h2>

                <?php if (isset($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3 mx-auto" style="max-width:500px;">
                    <div class="card-body">
                        <form method="post" class="mx-auto" style="max-width:400px;">
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required
                                    value="<?= $dayoff['tanggal'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="description" class="form-control" required
                                    value="<?= $dayoff['keterangan'] ?? '' ?>">
                            </div>
                            <button type="submit" class="btn btn-primary shadow-sm rounded-3">
                                <i class="fas fa-save me-2"></i> <?= isset($dayoff) ? "Update" : "Simpan" ?>
                            </button>
                            <a href="index.php?page=dayoff" class="btn btn-outline-secondary shadow-sm rounded-3 ms-2">
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