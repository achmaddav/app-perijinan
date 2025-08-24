<?php
require_once __DIR__ . "/../models/User.php";

class UserController
{
    private $user;

    public function __construct($db)
    {
        $this->user = new User($db);
    }

    public function login()
    {
        include __DIR__ . "/../views/login.php";
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $nip = trim($_POST['nip']);
            $password = $_POST['password'];

            $user = $this->user->findByNip($nip);

            if ($user) {
                $dbPassword = $user['password'];

                // Cek apakah hash masih SHA256 (panjang 64 hex) atau sudah bcrypt (60 karakter)
                if (strlen($dbPassword) === 64 && ctype_xdigit($dbPassword)) {
                    // Hash lama pakai SHA256
                    if (hash('sha256', $password) === $dbPassword) {
                        // ✅ Login sukses → rehash pakai bcrypt
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $this->user->updatePassword($user['id'], $newHash);
                    } else {
                        $this->failed();
                    }
                } else {
                    // Hash baru pakai bcrypt
                    if (!password_verify($password, $dbPassword)) {
                        $this->failed();
                    }
                }

                // Login success → set session
                $mulai_kerja = new DateTime($user['tanggal_mulai_kerja']);
                $sekarang = new DateTime();
                $diff = $mulai_kerja->diff($sekarang);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['jabatan'] = $user['kode_jabatan'];
                $_SESSION['kepala_balai'] = $user['kepala_id'];
                $_SESSION['atasan'] = $user['atasan_id'];
                $_SESSION['divisi'] = $user['divisi'];
                $_SESSION['nip'] = $user['nip'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['masa_kerja'] = $diff->y . ' tahun ' . $diff->m . ' bulan';
                header("Location: dashboard");
                exit();
            }
            $this->failed();
        }
    }

    private function failed() {
        echo "<script>alert('Login gagal! Periksa nip dan password.'); window.location.href='login';</script>";
        exit();
    }


    public function userInfo() {
        $user = $this->user->getUserById($_SESSION['user_id']);
        
        if ($user) {
            require_once __DIR__ . '/../views/common-users/user_info.php';
        } else {
            $_SESSION['error'] = "Info user tidak ditemukan.";
            header("Location: dashboard");
            exit();
        }
    }

    public function editProfil() {
        $id = $_SESSION['user_id'];
        $user = $this->user->getUserById($id); // ambil data user dari DB

        require_once __DIR__ . '/../views/common-users/update_user_profile.php'; // load halaman edit
    }

    public function processUpdateProfil()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $nama = $_POST['user_nama'];
            $tanggal_lahir = $_POST['birth_of_date'];
            $tempat_lahir = $_POST['place_of_birth'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $alamat = $_POST['address'];

            $result = $this->user->updateProfil(
                $id, $nama, $tanggal_lahir, $tempat_lahir, $email, $phone_number, $alamat
            );

            if ($result) {
                $_SESSION['success'] = "Data profil berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data.";
            }

            header("Location: profil");
            exit();
        }
    }

    public function editPassword() {
        $id = $_SESSION['user_id'];
        $user = $this->user->getUserById($id); 

        require_once __DIR__ . '/../views/common-users/update_password.php'; 
    }

    public function processUpdatePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $password_lama = $_POST['password_lama'];
            $password_baru = $_POST['password_baru'];
            $konfirmasi = $_POST['konfirmasi_password'];

            $user = $this->user->getUpdateUserById($id);

            // validasi password lama
            if (!password_verify($password_lama, $user['password'])) {
                $_SESSION['error'] = "Password lama tidak sesuai.";
                header("Location: edit_password");
                exit;
            }

            // cek konfirmasi
            if ($password_baru !== $konfirmasi) {
                $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok.";
                header("Location: edit_password");
                exit;
            }

            // hash password baru
            $hash = password_hash($password_baru, PASSWORD_BCRYPT);

            // update lewat model
            // update lewat model
            if ($this->user->updatePassword($id, $hash)) {
                $_SESSION['password_updated'] = true; // flag untuk trigger modal
                header("Location: /app-perijinan/edit_password");
                exit;
            } else {
                $_SESSION['error'] = "Gagal memperbarui password.";
                header("Location: /app-perijinan/edit_password");
                exit;
            }
        }
    }
}
