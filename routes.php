<?php
function route($conn) {
    // Jika user belum login, alihkan ke halaman login
    if (!isset($_SESSION['user_id']) && !isset($_GET['page'])) {
        require_once '../app/views/login.php';
        exit();
    }

    // Ambil parameter `page` dari URL, default ke `login`
    $page = isset($_GET['page']) ? $_GET['page'] : 'login';

    // Daftar halaman yang memerlukan login
    $protected_pages = [
        'dashboard',
        'daftar_pegawai',
        'user_detail',
        'ajukan_perizinan',
        'status_perizinan',
        'hapus_perizinan',
        'daftar_perizinan',
        'proses_perizinan',
        'persetujuan_perizinan',
        'laporan_perizinan',
        'verifikasi',
        'verify_keluar',
        'verify_masuk',
        'profil'
    ];

    // Redirect ke login jika halaman yang diakses butuh autentikasi
    if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

    // Routing berdasarkan halaman
    switch ($page) {
        case 'login':
            require_once '../app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->login();
            break;

        case 'authenticate':
            require_once '../app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->authenticate();
            break;

        case 'dashboard':
            require_once '../app/views/dashboard.php';
            break;

        case 'logout':
            session_destroy();
            header("Location: index.php?page=login");
            exit();

        case 'daftar_pegawai':
                require_once '../app/controllers/AtasanController.php';
                $controller = new AtasanController($conn);
                $controller->getUsers();
                break;

        case 'user_detail':
                require_once '../app/controllers/AtasanController.php';
                $controller = new AtasanController($conn);
                $controller->getUserDetail();
                break;

        case 'ajukan_perizinan':
            require_once '../app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->ajukanPerizinan();
            } else {
                $controller->formAjukanPerizinan();
            }
            break;

        case 'status_perizinan':
            require_once '../app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            $controller->statusPerizinan();
            break;

        case 'hapus_perizinan':
            require_once '../app/controllers/PerizinanController.php';
            $controller = new PerizinanController($conn);
            $controller->hapusPerizinan();
            break;

        case 'daftar_perizinan':
            require_once '../app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->listPerizinan();
            break;

        case 'proses_perizinan':
            require_once '../app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->prosesPerizinan();
            break;

        case 'persetujuan_perizinan':
            require_once '../app/views/persetujuan_perizinan.php';
            break;

        case 'laporan_perizinan':
            require_once '../app/controllers/LaporanController.php';
            $controller = new LaporanController($conn);
            $controller->laporanPerizinan();
            break;    

        case 'verifikasi':
            require_once '../app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyList();
            break;
        case 'verify_keluar':
            require_once '../app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyKeluar();
            break;
        case 'verify_masuk':
            require_once '../app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyMasuk();
            break;
        case 'profil':
            require_once '../app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->userInfo();
            break;

        default:
            echo "Halaman tidak ditemukan!";
            break;
    }
}
?>
