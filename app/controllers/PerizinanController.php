<?php
require_once '../config/database.php';
require_once '../app/models/PerizinanModel.php';

class PerizinanController {
    private $model;

    public function __construct($db) {
        $this->model = new PerizinanModel($db);
    }

    // Menampilkan halaman form pengajuan perizinan
    public function formAjukanPerizinan() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $jabatan = $_SESSION['jabatan'] ?? '';
        $atasanList = [];

        if ($jabatan === 'User') {
            $atasanList = $this->model->getAtasanList('Atasan');
        } else if ($jabatan === 'Atasan') {
            $atasanList = $this->model->getAtasanList('SuperUser');
        }

        require_once '../app/views/pengaju/ajukan_perizinan.php';
    }

    // Menangani penyimpanan data perizinan
    public function ajukanPerizinan() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = "Anda harus login untuk mengajukan perizinan.";
                header("Location: index.php?page=login");
                exit();
            }

            $user_id = $_SESSION['user_id'];
            $alasan = trim($_POST['alasan'] ?? '');
            $atasan_id = $_POST['atasan'] ?? null;
            $tanggal_rencana_keluar = $_POST['tanggal_keluar'] ?? null;
            $durasi_keluar = $_POST['durasi'] ?? null;

            if (empty($alasan)) {
                $_SESSION['error'] = "Alasan perizinan tidak boleh kosong.";
                header("Location: index.php?page=ajukan_perizinan");
                exit();
            }

            $result = $this->model->insertPerizinan($user_id, $alasan, $tanggal_rencana_keluar, $durasi_keluar, $atasan_id);

            if ($result === true) {
                $_SESSION['success'] = "Pengajuan perizinan berhasil dikirim.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan: " . $result;
            }
            session_write_close();

            header("Location: index.php?page=ajukan_perizinan");
            exit();
        }
    }

    // Menampilkan riwayat perizinan user
    public function riwayatPerizinan() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $dataPerizinan = $this->model->getRiwayatPerizinan($user_id);

        require_once '../app/views/pengaju/riwayat_perizinan.php';
    }
}
?>
