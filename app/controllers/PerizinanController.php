<?php
require_once __DIR__ . '/../models/PerizinanModel.php';

class PerizinanController {
    private $model;

    public function __construct($db) {
        $this->model = new PerizinanModel($db);
    }

    // Menampilkan halaman form pengajuan perizinan
    public function formAjukanPerizinan() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $jabatan = $_SESSION['jabatan'] ?? '';
        $atasanList = [];

        if ($jabatan === 'User') {
            $atasanList = $this->model->getAtasanList('Atasan');
        } else if ($jabatan === 'Atasan') {
            $atasanList = $this->model->getAtasanList('SuperUser');
        }

        require_once __DIR__ . '/../views/pengaju/ajukan_perizinan.php';
    }

    // Menangani penyimpanan data perizinan
    public function ajukanPerizinan() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $user_id = $_SESSION['user_id'];
            $alasan = trim($_POST['alasan'] ?? '');
            $atasan_id = $_POST['atasan'] ?? null;
            $tanggal_rencana_keluar = $_POST['tanggal_keluar'] ?? null;
            $durasi_keluar = $_POST['durasi'] ?? null;

            if (empty($alasan)) {
                $_SESSION['error'] = "Alasan perizinan tidak boleh kosong.";
                header("Location: ajukan_perizinan");
                exit();
            }

            $result = $this->model->insertPerizinan($user_id, $alasan, $tanggal_rencana_keluar, $durasi_keluar, $atasan_id);

            if ($result === true) {
                $_SESSION['success'] = "Pengajuan perizinan berhasil dikirim.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan: " . $result;
            }
            session_write_close();

            header("Location: ajukan_perizinan");
            exit();
        }
    }

    // Menampilkan riwayat perizinan user
    public function statusPerizinan() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $dataPerizinan = $this->model->getStatusPerizinan($user_id);

        require_once __DIR__ . '/../views/pengaju/status_perizinan.php';
    }

    public function hapusPerizinan()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID tidak valid.";
            header("Location: status_perizinan");
            exit;
        }

        $id = $_GET['id'];
        $status = $this->model->getStatus($id);

        if (!$status) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header("Location: status_perizinan");
            exit;
        }

        if ($status['status'] === 'Approved' || $status['status'] === 'Rejected') {
            $_SESSION['error'] = "Data tidak bisa dihapus karena status sudah " . $status['status'];
            header("Location: status_perizinan");
            exit;
        }

        if ($this->model->delete($id)) {
            $_SESSION['success'] = "Data berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data.";
        }
        
        header("Location: status_perizinan");
        exit;
    }

}
?>
