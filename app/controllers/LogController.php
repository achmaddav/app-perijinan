<?php
require_once __DIR__ . "/../models/LogKeluarMasukModel.php";
require_once __DIR__ . "/../models/PerizinanModel.php";

class LogController
{
    private $logKeluarMasuk;
    private $perizinanModel;

    public function __construct($db)
    {
        $this->logKeluarMasuk = new LogKeluarMasukModel($db);
        $this->perizinanModel = new PerizinanModel($db);
    }

    public function verifyList()
    {
        $izinList = $this->perizinanModel->getApprovedRequests();
        require_once __DIR__ . '/../views/satpam/verifikasi.php';
    }

    public function historyVerify()
    {
        $historyList = $this->logKeluarMasuk->getHistoryOutIn();
        require_once __DIR__ . '/../views/satpam/history_verifikasi.php';
    }

    public function verifyKeluar()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'Satpam') {
            header("Location: dashboard");
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

            header("Location: verifikasi");
            exit();
        }
    }

    public function verifyMasuk()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'Satpam') {
            header("Location: dashboard");
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

            header("Location: verifikasi");
            exit();
        }
    }
}
?>
