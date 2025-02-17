<?php
include_once "../config/database.php";
include_once "../app/models/User.php";

class UserController
{
    private $db;
    private $user;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user = new User($this->db);
    }

    public function login()
    {
        include "../app/views/login.php";
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->user->findByEmail($email);

            if ($user) {
                $hashedPassword = hash('sha256', $password);
                if ($hashedPassword === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['jabatan'] = $user['jabatan'];
                    $_SESSION['nip'] = $user['nip'];

                    header("Location: index.php?page=dashboard");
                    exit();
                }
            }

            echo "<script>alert('Login gagal! Periksa email dan password.'); window.location.href='index.php?page=login';</script>";
            exit();
        }
    }
}
