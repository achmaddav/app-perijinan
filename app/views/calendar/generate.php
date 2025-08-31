<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">Generate Kelender</h2>

                <!-- Notifikasi -->
                <?php if (isset($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-3 mx-auto" style="max-width:500px;">
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Start Year:</label>
                                <input type="number" name="start_year" value="<?= date('Y') ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Year:</label>
                                <input type="number" name="end_year" value="<?= date('Y') ?>" class="form-control">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success shadow-sm rounded-3">
                                    <i class="fas fa-calendar-plus me-2"></i> Generate
                                </button>
                                <a href="index.php?page=calendar" class="btn btn-outline-primary shadow-sm rounded-3 ms-2">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .alert {
        animation: fadeIn 0.5s, fadeOut 0.5s 3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }

    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
