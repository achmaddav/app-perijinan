<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="wrapper">

    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h2 class="text-center py-3 text-primary">Daftar Tanggal Merah</h2>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-dayoff" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data)): ?>
                                        <?php foreach ($data as $index => $row): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                                <td>
                                                    <a href="index.php?page=dayoff_edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary shadow-sm rounded-3">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="index.php?page=dayoff_delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger shadow-sm rounded-3" onclick="return confirm('Yakin hapus dayoff ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">Tidak ada data.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                            <form method="get" class="d-inline-block">
                                <input type="hidden" name="page" value="dayoff">
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text fw-bold">Pilih tahun :</span>
                                    <input type="number" 
                                        name="year" 
                                        value="<?= $_GET['year'] ?? date('Y') ?>" 
                                        class="form-control text-center">
                                    <button type="submit" class="btn btn-primary">Lihat</button>
                                </div>
                            </form>
                            <a href="index.php?page=dayoff_create" class="btn btn-success shadow-sm rounded-3">
                                <i class="fas fa-plus me-2"></i> Tambah Tanggal Merah
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>