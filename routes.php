<?php
function route($conn) {
    // Ambil parameter 'page' dari URL, default ke 'login'
    $page = isset($_GET['page']) ? $_GET['page'] : 'login';

    // Daftar halaman yang memerlukan autentikasi
    $protected_pages = [
        'dashboard',
        'daftar_pegawai',
        'user_detail',
        'ajukan_perizinan',
        'status_perizinan',
        'hapus_perizinan',
        'daftar_perizinan',
        'proses_perizinan',
        'laporan_perizinan',
        'verifikasi',
        'verify_keluar',
        'verify_masuk',
        'profil'
    ];

    // Redirect ke halaman login jika halaman yang diakses butuh autentikasi
    if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
        // header("Location: /app-perijinan/login");
        header("Location: /app-perijinan/login");
        exit();
    }

    // Daftar role yang diperbolehkan untuk masing-masing halaman
    $role_permissions = [
        'dashboard'             => ['User', 'Atasan', 'SuperUser', 'Satpam'],
        'daftar_pegawai'        => ['SuperUser'],
        'user_detail'           => ['SuperUser'],
        'ajukan_perizinan'      => ['User', 'Atasan'],
        'status_perizinan'      => ['User', 'Atasan'],
        'hapus_perizinan'       => ['User', 'Atasan'],
        'daftar_perizinan'      => ['Atasan', 'SuperUser'],
        'proses_perizinan'      => ['Atasan', 'SuperUser'],
        'laporan_perizinan'     => ['Atasan', 'SuperUser'],
        'verifikasi'            => ['Satpam'],
        'verify_keluar'         => ['Satpam'],
        'verify_masuk'          => ['Satpam'],
        'profil'                => ['User', 'Atasan', 'SuperUser', 'Satpam']
    ];

    // Jika halaman memiliki batasan role, periksa apakah pengguna memiliki izin
    if (isset($role_permissions[$page])) {
        $user_role = $_SESSION['jabatan'] ?? ''; 
    
        if (!in_array($user_role, $role_permissions[$page])) {
            echo '
            <div style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f8d7da;color:#721c24;text-align:center;padding:20px;border-radius:10px;font-family:sans-serif;">
                <div style="max-width:400px;">
                    <h2 style="margin-bottom:10px;">⛔ Akses Ditolak</h2>
                    <p style="margin-bottom:20px;">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    <a href="/app-perijinan/dashboard" style="display:inline-block;padding:10px 20px;background:#dc3545;color:white;text-decoration:none;border-radius:5px;">Kembali</a>
                </div>
            </div>';
            exit();
        }
    }      

    // Routing berdasarkan halaman
    switch ($page) {
        case 'login':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->login();
            break;

        case 'authenticate':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->authenticate();
            break;

        case 'dashboard':
            require_once __DIR__ . '/app/views/dashboard.php';
            break;

        case 'logout':
            session_destroy();
            header("Location: /app-perijinan/login");
            exit();

        case 'daftar_pegawai':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->getUsers();
            break;

        case 'user_detail':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->getUserDetail();
            break;

        case 'ajukan_perizinan':
            require_once __DIR__ . '/app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->ajukanPerizinan();
            } else {
                $controller->formAjukanPerizinan();
            }
            break;

        case 'status_perizinan':
            require_once __DIR__ . '/app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            $controller->statusPerizinan();
            break;

        case 'hapus_perizinan':
            require_once __DIR__ . '/app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            $controller->hapusPerizinan();
            break;

        case 'daftar_perizinan':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->listPerizinan();
            break;

        case 'proses_perizinan':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->prosesPerizinan();
            break;

        case 'laporan_perizinan':
            require_once __DIR__ . '/app/controllers/LaporanController.php';
            $controller = new LaporanController($conn);
            $controller->laporanPerizinan();
            break;

        case 'verifikasi':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyList();
            break;

        case 'verify_keluar':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyKeluar();
            break;

        case 'verify_masuk':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyMasuk();
            break;

        case 'profil':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->userInfo();
            break;

        default:
            echo "Halaman tidak ditemukan!";
            break;
    }
}
?>
