<?php
require_once '../app/models/PerizinanModel.php';
require_once '../app/models/User.php';

class AtasanController {
    private $perizinanModel;
    private $userModel;

    public function __construct($db) {
        $this->perizinanModel = new PerizinanModel($db);
        $this->userModel = new User($db);
    }

    // Menampilkan daftar perizinan yang menunggu persetujuan
    public function listPerizinan() {
        $dataPerizinan = $this->perizinanModel->getPendingPerizinan();
        require_once '../app/views/atasan/daftar_perizinan.php';
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
                header("Location: index.php?page=daftar_perizinan");
                exit();
            }
    
            if ($this->perizinanModel->updateStatus($id, $status, $approved_by)) {
                $_SESSION['success'] = "Perizinan berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat memperbarui perizinan.";
            }
    
            header("Location: index.php?page=daftar_perizinan");
            exit();
        }
    }    

    public function getUsers() {
        $users = $this->userModel->getAllUser();
        require_once '../app/views/atasan/daftar_user.php';
    }

    public function getUserDetail() {
        // Pastikan request menggunakan metode POST dan parameter id tersedia
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
            $id = (int) $_POST['id']; // casting ke integer untuk keamanan

            // Ambil detail user dari model
            $user = $this->userModel->findDetailUser($id);

            // Jika user ditemukan, tampilkan view detail
            if ($user) {
                require_once '../app/views/atasan/detail_user.php';
            } else {
                // Jika user tidak ditemukan, set pesan error dan redirect kembali ke daftar user
                $_SESSION['error'] = "User tidak ditemukan.";
                header("Location: index.php?page=daftar_pegawai");
                exit();
            }
        } else {
            // Jika tidak ada id atau metode tidak POST, redirect ke halaman daftar user
            header("Location: index.php?page=daftar_pegawai");
            exit();
        }
    }
}
?>
