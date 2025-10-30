<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../models/User.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

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

                $_SESSION['success'] = "Login berhasil!";

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

    private function failed()
    {
        $_SESSION['error'] = "Login gagal! Periksa NIP dan Password Anda.";
        header("Location: login");
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

    public function resetPassword()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID user tidak ditemukan!";
            header("Location: daftar_pegawai");
            exit;
        }

        $userId = intval($_GET['id']);

        // Opsional: cek apakah user ada
        $user = $this->user->getUserById($userId);
        if (!$user) {
            $_SESSION['error'] = "User tidak ditemukan!";
            header("Location: daftar_pegawai");
            exit;
        }

        // Reset password
        $success = $this->user->resetPasswordById($userId);

        if ($success) {
            $_SESSION['success'] = "Password user '{$user['nama']}' berhasil di-reset ke default (123456).";
        } else {
            $_SESSION['error'] = "Gagal mereset password user '{$user['nama']}'.";
        }

        header("Location: daftar_pegawai");
        exit;
    }

    public function nonaktifkanUser()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID user tidak ditemukan.";
            header("Location: daftar_pegawai");
            exit;
        }

        $id = (int) $_GET['id'];

        if ($this->user->nonaktifkanById($id)) {
            $_SESSION['success'] = "Pegawai berhasil dinonaktifkan.";
        } else {
            $_SESSION['error'] = "Gagal menonaktifkan pegawai.";
        }

        header("Location: daftar_pegawai");
        exit;
    }

    public function import_user_excel()
    {
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                'success' => false,
                'message' => "Gagal upload file! Pastikan file yang diupload benar."
            ]);
            exit;
        }

        $fileTmp  = $_FILES['file_excel']['tmp_name'];
        $fileName = $_FILES['file_excel']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xls', 'xlsx'])) {
            echo json_encode([
                'success' => false,
                'message' => "Format file tidak didukung! Hanya file Excel (.xls, .xlsx) yang diperbolehkan."
            ]);
            exit;
        }

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileTmp);
            $spreadsheet = $reader->load($fileTmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // --- pisahkan data per prioritas jabatan ---
            $bucket = [
                'KEP' => [],
                'KTA' => [],
                'STF' => [],
                'OTHER' => []
            ];

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // skip header

                $data = [
                    'nama'                => trim($row[0]),
                    'birth_of_date'       => trim($row[1]),
                    'place_of_birth'      => trim($row[2]),
                    'nip'                 => trim($row[3]),
                    'email'               => trim($row[4]),
                    'phone_number'        => trim($row[5]),
                    'address'             => trim($row[6]),
                    'jabatan_kode'        => trim($row[7]),
                    'divisi_kode'         => trim($row[8]),
                    'kepala_nip'          => trim($row[9]),
                    'atasan_nip'          => trim($row[10]),
                    'tanggal_mulai_kerja' => trim($row[11]),
                    '_row_index'          => $index
                ];

                // kelompokkan sesuai prioritas
                if ($data['jabatan_kode'] === 'KEP') {
                    $bucket['KEP'][] = $data;
                } elseif ($data['jabatan_kode'] === 'KTA') {
                    $bucket['KTA'][] = $data;
                } elseif ($data['jabatan_kode'] === 'STF') {
                    $bucket['STF'][] = $data;
                } else {
                    $bucket['OTHER'][] = $data;
                }
            }

            // --- urutan eksekusi sesuai prioritas ---
            $orderedData = array_merge(
                $bucket['KEP'],
                $bucket['KTA'],
                $bucket['STF'],
                $bucket['OTHER']
            );

            // --- proses insert/update sesuai urutan ---
            foreach ($orderedData as $data) {
                $index = $data['_row_index'];

                if (empty($data['nip']) || empty($data['nama'])) {
                    $errorCount++;
                    $errors[] = "Baris $index: NIP atau Nama kosong";
                    continue;
                }

                if ($this->user->insertFromExcel($data)) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Baris $index: Gagal menyimpan data ke database";
                }
            }

            if ($successCount > 0) {
                $message = "Import selesai! $successCount data berhasil diproses.";
                if ($errorCount > 0) {
                    $message .= " $errorCount data gagal diproses.";
                }
            } else {
                $message = "Tidak ada data yang berhasil diproses. $errorCount data gagal.";
            }

            echo json_encode([
                'success' => $successCount > 0,
                'message' => $message,
                'stats'   => [
                    'success' => $successCount,
                    'error'   => $errorCount
                ],
                'errors'  => $errors
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ]);
        }
        exit;
    }


}
