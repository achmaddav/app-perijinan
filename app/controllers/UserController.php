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
                $hashedPassword = hash('sha256', $password);
                if ($hashedPassword === $user['password']) {
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
            }

            echo "<script>alert('Login gagal! Periksa nip dan password.'); window.location.href='login';</script>";
            exit();
        }
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
}
