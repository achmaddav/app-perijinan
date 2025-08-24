<?php
require_once __DIR__ . '/../models/PerizinanModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/CutiModel.php';

class AtasanController {
    private $perizinanModel;
    private $userModel;
    private $cutiModel;

    public function __construct($db) {
        $this->perizinanModel = new PerizinanModel($db);
        $this->userModel = new User($db);
        $this->cutiModel = new CutiModel($db);
    }

    // Menampilkan daftar perizinan yang menunggu persetujuan
    public function listPerizinan() {
        $dataPerizinan = $this->perizinanModel->getPendingPerizinan();
        require_once __DIR__ . '/../views/atasan/daftar_perizinan.php';
    }

    // Atasan menyetujui atau menolak perizinan
    public function prosesPerizinan() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['status'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $approved_by = $_SESSION['user_id'];
    
            // Konversi status dari input menjadi nilai yang sesuai di database
            if ($status === "Disetujui") {
                $status = "Approved";
            } elseif ($status === "Ditolak") {
                $status = "Rejected";
            } else {
                $_SESSION['error'] = "Status tidak valid.";
                header("Location: daftar_perizinan");
                exit();
            }
    
            if ($this->perizinanModel->updateStatus($id, $status, $approved_by)) {
                $_SESSION['success'] = "Perizinan berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat memperbarui perizinan.";
            }
    
            header("Location: daftar_perizinan");
            exit();
        }
    }    

    public function getUsers() {
        $id = $_SESSION['user_id'];
        $users = $this->userModel->getAllUser($id);
        require_once __DIR__ . '/../views/atasan/daftar_user.php';
    }

    public function getUserDetail() {
        // Pastikan request menggunakan metode POST dan parameter id tersedia
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            // Ambil detail user dari model
            $user = $this->userModel->findDetailUser($id);
            $rincian = $this->userModel->rincianPerizinan($id);
            $rincianNonBerizin = $this->userModel->rincianNonBerizin($id);

            require_once __DIR__ . '/../views/atasan/detail_user.php';
        } else {
            // Jika tidak ada id atau metode tidak POST, redirect ke halaman daftar user
            header("Location: daftar_pegawai");
            exit();
        }
    }

    public function daftar_pengajuan_cuti() {
        $jabatan = $_SESSION['jabatan'] ?? null;

        if ($jabatan === "KTA") {
            $daftarPengajuanCuti = $this->cutiModel->getPengajuanCuti(); 
        } else {
            $daftarPengajuanCuti = $this->cutiModel->getPengajuanCutiForHeadOffice();
        }

        require_once __DIR__ . '/../views/atasan/daftar_pengajuan_cuti.php';
    }

    public function proses_approval_cuti() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['status'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $approved_by = $_SESSION['user_id'];
            
            if ($_SESSION['jabatan'] == "KTA") {
                if ($this->cutiModel->updateStatus($id, $status, $approved_by)) {
                    $_SESSION['success'] = "Cuti berhasil diperbarui.";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat menyetujui cuti.";
                }
            } else {
                if ($this->cutiModel->updateStatusByHeadOffice($id, $status, $approved_by)) {
                    $_SESSION['success'] = "Cuti berhasil diperbarui.";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat menyetujui cuti.";
                }
            }
            
            header("Location: daftar_pengajuan_cuti");
            exit();
        }
    }
}
?>
