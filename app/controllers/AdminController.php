<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DivisiModel.php';
require_once __DIR__ . '/../models/JabatanModel.php';

class AdminController {
    private $userModel;
    private $divisiModel;
    private $jabatanModel;

    public function __construct($db) {
        $this->userModel = new User($db);
        $this->divisiModel = new DivisiModel($db);
        $this->jabatanModel = new JabatanModel($db);
    }

    public function user_add() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $divisions = $this->divisiModel->getAllDivisi();
        $positions = $this->jabatanModel->getAllPosition();
        $ketua_tim_list = $this->userModel->getAllLeader();
        $head_office_list = $this->userModel->getAllHead();

        require_once __DIR__ . '/../views/admin/user_add.php';
    }

    public function insert_user()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $nama = $_POST['nama'];
            $tanggal_lahir = $_POST['tanggal_lahir'];
            $tempat_lahir = $_POST['tempat_lahir'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $alamat = $_POST['alamat'];
            $nip = $_POST['nip'];
            $jenisJabatan = $_POST['jenisJabatan'];
            $timKerja = $_POST['timKerja'] ?? null;
            $ketua_timker = $_POST['ketua_timker'] ?? null;
            $kepala_balai = $_POST['kepala_balai'] ?? null;
            $tanggal_kerja = $_POST['tanggal_kerja'];

            $result = $this->userModel->insertUser($nama, $tanggal_lahir, $tempat_lahir, 
                $email, $phone_number, $alamat, $nip, $jenisJabatan, $timKerja,
                $ketua_timker, $kepala_balai, $tanggal_kerja);

            if ($result === true) {
                $_SESSION['success'] = "Data pegawai berhasil disimpan.";
                session_write_close();
                header("Location: daftar_pegawai");
                exit();
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data pegawai.";
                session_write_close();
                header("Location: insert_user");
                exit();
            }
        }
    }

    public function editUser()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: daftar_pegawai");
            exit();
        }

        $user = $this->userModel->getUpdateUserById($id);
        $divisions = $this->divisiModel->getAllDivisi();
        $positions = $this->jabatanModel->getAllPosition();
        $ketua_tim_list = $this->userModel->getAllLeader();
        $head_office_list = $this->userModel->getAllHead();
 
        require_once __DIR__ . '/../views/admin/user_update.php';
    }

    public function processUserUpdate()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $tanggal_lahir = $_POST['tanggal_lahir'];
            $tempat_lahir = $_POST['tempat_lahir'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $alamat = $_POST['alamat'];
            $nip = $_POST['nip'];
            $jenisJabatan = $_POST['jenisJabatan'];
            $timKerja = $_POST['timKerja'] ?? null;
            $ketua_timker = $_POST['ketua_timker'] ?? null;
            $kepala_balai = $_POST['kepala_balai'] ?? null;
            $tanggal_kerja = $_POST['tanggal_kerja'];

            $result = $this->userModel->updateUser(
                $id, $nama, $tanggal_lahir, $tempat_lahir, $email, $phone_number,
                $alamat, $nip, $jenisJabatan, $timKerja, $ketua_timker, $kepala_balai, $tanggal_kerja
            );

            if ($result) {
                $_SESSION['success'] = "Data pegawai berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data.";
            }
            header("Location: daftar_pegawai");
            exit();
        }
    }
}
?>
