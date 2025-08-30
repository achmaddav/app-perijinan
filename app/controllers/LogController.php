<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/LogKeluarMasukModel.php";
require_once __DIR__ . "/../models/PerizinanModel.php";
require_once __DIR__ . "/../models/PerizinanNonApprovalModel.php";

class LogController
{
    private $user;
    private $logKeluarMasuk;
    private $perizinanModel;
    private $perizinanNonApprovalModel;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->logKeluarMasuk = new LogKeluarMasukModel($db);
        $this->perizinanModel = new PerizinanModel($db);
        $this->perizinanNonApprovalModel = new PerizinanNonApprovalModel($db);
    }

    public function verifyList()
    {
        $izinList = $this->perizinanModel->getApprovedRequests();
        require_once __DIR__ . '/../views/satpam/verifikasi.php';
    }

    public function verifyNonPerizinanList()
    {
        $id = $_SESSION['user_id'];
        $nonIzinList = $this->perizinanNonApprovalModel->getNonPerizinanRequests();
        $userList = $this->user->getAllUser($id);
        require_once __DIR__ . '/../views/satpam/verifikasi_non_perizinan.php';
    }

    public function historyVerify()
    {
        $historyList = $this->logKeluarMasuk->getHistoryOutIn();
        require_once __DIR__ . '/../views/satpam/history_verifikasi.php';
    }

    public function verifyKeluar()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'SCT') {
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
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'SCT') {
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

    public function verifyNonPerizinan() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validasi input
            $user_id = isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : null;
            $satpam_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $waktu_keluar = (new DateTime())->format('Y-m-d H:i:s');
    
            if (!$user_id) {
                $_SESSION['error'] = "Semua field harus diisi.";
                header("Location: verifikasi_non_perizinan");
                exit();
            }
    
            // Masukkan ke database
            $result = $this->perizinanNonApprovalModel->insertNonPerizinan($user_id, $satpam_id, $waktu_keluar);
    
            if ($result === true) {
                $_SESSION['success'] = "Pengajuan perizinan berhasil dikirim.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan: " . $result;
            }
    
            session_write_close();
            header("Location: verifikasi_non_perizinan");
            exit();
        }
    }

    public function verifyMasukNonPerizinan()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['jabatan'] !== 'SCT') {
            header("Location: dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $non_perizinan_id = $_POST['non_perizinan_id'];
            $log = $this->perizinanNonApprovalModel->findLogByNonPerizinanId($non_perizinan_id);

            if ($log) {
                if ($this->perizinanNonApprovalModel->updateMasukNonPerizinan($log['id'])) {
                    $_SESSION['success'] = "Verifikasi masuk berhasil.";
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat verifikasi masuk.";
                }
            } else {
                $_SESSION['error'] = "Log keluar tidak ditemukan.";
            }

            header("Location: verifikasi_non_perizinan");
            exit();
        }
    }
    
    public function historyVerifyNonPerizinan()
    {
        $historyList = $this->perizinanNonApprovalModel->getHistoryNonPerizinan();
        require_once __DIR__ . '/../views/satpam/history_verifikasi_non_perizinan.php';
    }
}
?>
