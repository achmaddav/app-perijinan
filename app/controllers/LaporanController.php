<?php

require_once __DIR__ . "/../models/PerizinanModel.php";

class LaporanController {
    private $model;

    public function __construct($db) {
        $this->model = new PerizinanModel($db);
    }

    public function laporanPerizinan() {
        $limit = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = max(0, ($page - 1) * $limit);

        // Ambil data laporan perizinan berdasarkan pagination dan bulan saat ini
        $data = $this->model->getLaporanPerizinan($offset, $limit);
        $totalData = $this->model->countTotalLaporan();
        $totalPages = ceil($totalData / $limit);

        // Pastikan halaman tidak melebihi batas total halaman yang tersedia
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $data = $this->model->getLaporanPerizinan($offset, $limit);
        }

        require_once '../app/views/atasan/laporan_perizinan.php';
    }
}
?>
