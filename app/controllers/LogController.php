<?php
require_once "../config/Database.php";
require_once "../app/models/LogKeluarMasukModel.php";

class LogController
{
    private $logKeluarMasuk;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->logKeluarMasuk = new LogKeluarMasukModel($db);
    }

    public function verifyKeluar()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'Satpam') {
            header("Location: index.php?page=dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perizinan_id = $_POST['perizinan_id'];
            $satpam_id = $_SESSION['user_id'];

            if ($this->logKeluarMasuk->insertKeluar($perizinan_id, $satpam_id)) {
                $_SESSION['success'] = "Verifikasi keluar berhasil.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat verifikasi keluar.";
            }

            header("Location: index.php?page=verifikasi");
            exit();
        }
    }

    public function verifyMasuk()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'Satpam') {
            header("Location: index.php?page=dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perizinan_id = $_POST['perizinan_id'];
            $log = $this->logKeluarMasuk->findLogByPerizinanId($perizinan_id);

            if ($log) {
                if ($this->logKeluarMasuk->updateMasuk($log['id'])) {
                    $_SESSION['success'] = "Verifikasi masuk berhasil.";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat verifikasi masuk.";
                }
            } else {
                $_SESSION['error'] = "Log keluar tidak ditemukan.";
            }

            header("Location: index.php?page=verifikasi");
            exit();
        }
    }
}
?>
