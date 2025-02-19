<?php

require_once __DIR__ . "/../models/PerizinanModel.php";

class LaporanController {
    private $model;

    public function __construct($db) {
        $this->model = new PerizinanModel($db);
    }

    public function laporanPerizinan() {
        // Tentukan limit per halaman
        $limit = 10;
        // Ambil nomor halaman dengan validasi menggunakan filter_input
        $page = filter_input(INPUT_GET, 'page_no', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $offset = ($page - 1) * $limit;
        
        // Ambil filter dari GET dan sanitasi input-nya
        $month_filter = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $status_filter = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) ?: '';
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';
        
        $jabatan = $_SESSION['jabatan'] ?? '';

        // Ambil data laporan perizinan berdasarkan filter dan pagination
        $data = $this->model->getLaporanPerizinan($jabatan, $month_filter, $status_filter, $pemohon_filter, $limit, $offset);
        $totalData = $this->model->countTotalLaporan($jabatan, $month_filter, $status_filter, $pemohon_filter);
        $totalPages = ceil($totalData / $limit);
        
        // Pastikan halaman tidak melebihi batas total halaman
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $data = $this->model->getLaporanPerizinan($jabatan, $month_filter, $status_filter, $pemohon_filter, $limit, $offset);
        }
        
        // Sertakan view dan kirim variabel yang diperlukan
        require_once '../app/views/atasan/laporan_perizinan.php';
    }
}
?>

