<?php

require_once __DIR__ . "/../models/PerizinanModel.php";
require_once __DIR__ . "/../models/PerizinanNonApprovalModel.php";
require_once __DIR__ . "/../models/CutiModel.php";
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController {
    private $model;
    private $modelNonPerizinan;
    private $cutiModel;

    public function __construct($db) {
        $this->model = new PerizinanModel($db);
        $this->modelNonPerizinan = new PerizinanNonApprovalModel($db);
        $this->cutiModel = new CutiModel($db);
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
        
        $user_id = $_SESSION['user_id'];
        $jabatan = $_SESSION['jabatan'] ?? '';

        // Ambil data laporan perizinan berdasarkan filter dan pagination
        $data = $this->model->getLaporanPerizinan($user_id, $jabatan, $month_filter, $status_filter, $pemohon_filter, $limit, $offset);
        $totalData = $this->model->countTotalLaporan($user_id, $jabatan, $month_filter, $status_filter, $pemohon_filter);
        $totalPages = ceil($totalData / $limit);
        
        // Pastikan halaman tidak melebihi batas total halaman
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $data = $this->model->getLaporanPerizinan($user_id, $jabatan, $month_filter, $status_filter, $pemohon_filter, $limit, $offset);
        }
        
        // Sertakan view dan kirim variabel yang diperlukan
        require_once __DIR__ . '/../views/atasan/laporan_perizinan.php';
    }

    public function laporanNonPerizinan() {
        // Tentukan limit per halaman
        $limit = 10;
        // Ambil nomor halaman dengan validasi menggunakan filter_input
        $page = filter_input(INPUT_GET, 'page_no', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $offset = ($page - 1) * $limit;
        
        // Ambil filter dari GET dan sanitasi input-nya
        $month_filter = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';
        
        $user_id = $_SESSION['user_id'];

        // Ambil data laporan perizinan berdasarkan filter dan pagination
        $data = $this->modelNonPerizinan->getLaporanNonPerizinan($user_id, $month_filter, $pemohon_filter, $limit, $offset);
        $totalData = $this->modelNonPerizinan->countTotalLaporan($user_id, $month_filter, $pemohon_filter);
        $totalPages = ceil($totalData / $limit);
        
        // Pastikan halaman tidak melebihi batas total halaman
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $data = $this->modelNonPerizinan->getLaporanNonPerizinan($user_id, $month_filter, $pemohon_filter, $limit, $offset);
        }
        
        // Sertakan view dan kirim variabel yang diperlukan
        require_once __DIR__ . '/../views/atasan/laporan_non_perizinan.php';
    }

    public function laporanCuti() {
        // Tentukan limit per halaman
        $limit = 10;
        // Ambil nomor halaman dengan validasi menggunakan filter_input
        $page = filter_input(INPUT_GET, 'page_no', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $offset = ($page - 1) * $limit;
        
        // Ambil filter dari GET dan sanitasi input-nya
        $month_filter = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';
        
        $user_id = $_SESSION['user_id'];

        // Ambil data laporan perizinan berdasarkan filter dan pagination
        $data = $this->modelNonPerizinan->getLaporanNonPerizinan($user_id, $month_filter, $pemohon_filter, $limit, $offset);
        $totalData = $this->modelNonPerizinan->countTotalLaporan($user_id, $month_filter, $pemohon_filter);
        $totalPages = ceil($totalData / $limit);
        
        // Pastikan halaman tidak melebihi batas total halaman
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $data = $this->modelNonPerizinan->getLaporanNonPerizinan($user_id, $month_filter, $pemohon_filter, $limit, $offset);
        }
        
        // Sertakan view dan kirim variabel yang diperlukan
        require_once __DIR__ . '/../views/atasan/laporan_non_perizinan.php';
    }

    public function exportLaporanPerizinanExcel()
    {
        // Bersihkan buffer output (penting agar file tidak corrupt)
        if (ob_get_length()) ob_end_clean();

        // Ambil filter dari GET, fallback ke default
        $month_filter   = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $status_filter  = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) ?: '';
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';

        $user_id = $_SESSION['user_id'] ?? null;
        $jabatan = $_SESSION['jabatan'] ?? '';

        // Ambil semua data
        $data = $this->model->getLaporanPerizinanAll($user_id, $jabatan, $month_filter, $status_filter, $pemohon_filter);

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Laporan
        $sheet->setCellValue('A1', 'Laporan Perizinan');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = [
            'No', 'Nama Pemohon', 'Alasan', 'Status',
            'Tanggal Keluar', 'Durasi (Jam)', 'Approver',
            'Waktu Pengajuan', 'Aktual Waktu Keluar'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEFEFEF');
            $col++;
        }

        // Isi data
        $rowNum = 4;
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $row['nama_pemohon']);
            $sheet->setCellValue("C{$rowNum}", $row['alasan']);
            $sheet->setCellValue("D{$rowNum}", $row['status']);
            $sheet->setCellValue("E{$rowNum}", $row['tanggal_rencana_keluar']);
            $sheet->setCellValue("F{$rowNum}", $row['durasi_keluar']);
            $sheet->setCellValue("G{$rowNum}", $row['nama_atasan'] ?? '-');
            $sheet->setCellValue("H{$rowNum}", $row['created_at']);
            $sheet->setCellValue("I{$rowNum}", $row['total_waktu_keluar']);
            $rowNum++;
        }

        // Auto-size kolom
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border tabel
        $sheet->getStyle("A3:I" . ($rowNum - 1))
            ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Output file ke browser
        $filename = "Laporan_Perizinan_" . date('Y-m-d') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportLaporanCutiExcel()
    {
        // Bersihkan buffer output (penting agar file tidak corrupt)
        if (ob_get_length()) ob_end_clean();

        // Ambil filter dari GET
        $month_filter   = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $status_filter  = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) ?: '';
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';

        $user_id = $_SESSION['user_id'] ?? null;
        $jabatan = $_SESSION['jabatan'] ?? '';

        // Ambil semua data cuti sesuai filter
        $data = $this->cutiModel->getLaporanCutiAll($user_id, $jabatan, $month_filter, $status_filter, $pemohon_filter);

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Laporan
        $sheet->setCellValue('A1', 'Laporan Cuti');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = [
            'No', 'Nama Pemohon', 'Alasan', 'Lama Cuti (Hari)',
            'Tanggal Mulai Cuti', 'Tanggal Selesai Cuti',
            'Approver', 'Status', 'Waktu Pengajuan'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEFEFEF');
            $col++;
        }

        // Isi data
        $rowNum = 4;
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $row['nama_pemohon']);
            $sheet->setCellValue("C{$rowNum}", $row['alasan']);
            $sheet->setCellValue("D{$rowNum}", $row['lama_cuti']);
            $sheet->setCellValue("E{$rowNum}", $row['tanggal_mulai']);
            $sheet->setCellValue("F{$rowNum}", $row['tanggal_selesai']);
            $sheet->setCellValue("G{$rowNum}", $row['approver']);
            $sheet->setCellValue("H{$rowNum}", $row['status']);
            $sheet->setCellValue("I{$rowNum}", $row['tanggal_pengajuan']);
            $rowNum++;
        }

        // Auto-size kolom
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border tabel
        $sheet->getStyle("A3:I" . ($rowNum - 1))
            ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Output file ke browser
        $filename = "Laporan_Cuti_" . date('Y-m-d') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportLaporanNonPerizinanExcel()
    {
        // Bersihkan buffer output (penting agar file tidak corrupt)
        if (ob_get_length()) ob_end_clean();

        // Ambil filter dari GET, fallback ke default
        $month_filter   = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?: date('Y-m');
        $pemohon_filter = filter_input(INPUT_GET, 'pemohon', FILTER_SANITIZE_STRING) ?: '';

        $user_id = $_SESSION['user_id'] ?? null;

        // Ambil semua data
        $data = $this->modelNonPerizinan->getLaporanNonPerizinanAll($user_id, $month_filter, $pemohon_filter);

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Laporan
        $sheet->setCellValue('A1', 'Laporan Tidak Berizin');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = [
            'No', 'Nama', 'Tanggal Keluar', 'Total Waktu Keluar'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEFEFEF');
            $col++;
        }

        // Isi data
        $rowNum = 4;
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $row['nama']);
            $sheet->setCellValue("C{$rowNum}", $row['created_at']);
            $sheet->setCellValue("D{$rowNum}", $row['total_waktu_keluar']);
            $rowNum++;
        }

        // Auto-size kolom
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border tabel
        $sheet->getStyle("A3:D" . ($rowNum - 1))
            ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Output file ke browser
        $filename = "Laporan_Tidak_Berizin_" . date('Y-m-d') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


}
?>

