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
        'history_verify',
        'verifikasi_non_perizinan',
        'profil',
        'leave_history',
        'proses_approval_cuti',
        'laporan_cuti',
        'user_add',
        'insert_user',
        'user_edit',
        'process_user_update',
        'edit_profil',
        'process_update_profil',
        'edit_password',
        'process_update_password',
        'user_reset_password',
        'user_nonaktifkan',
        'user_reset_password',
        'user_nonaktifkan'
    ];

    // Redirect ke halaman login jika halaman yang diakses butuh autentikasi
    if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
        // header("Location: /app-perijinan/login");
        header("Location: /app-perijinan/login");
        exit();
    }

    // Daftar role yang diperbolehkan untuk masing-masing halaman
    $role_permissions = [
        'dashboard'                 => ['STF', 'KTA', 'KEP', 'SCT', 'ADM'],
        'daftar_pegawai'            => ['ADM', 'KEP'],
        'user_detail'               => ['ADM', 'KEP'],
        'ajukan_perizinan'          => ['STF', 'KTA'],
        'status_perizinan'          => ['STF', 'KTA'],
        'hapus_perizinan'           => ['STF', 'KTA'],
        'daftar_perizinan'          => ['KTA', 'KEP'],
        'proses_perizinan'          => ['KTA', 'KEP'],
        'laporan_perizinan'         => ['KTA', 'KEP', 'ADM'],
        'verifikasi'                => ['SCT'],
        'verify_keluar'             => ['SCT'],
        'verify_masuk'              => ['SCT'],
        'verifikasi_non_perizinan'  => ['SCT'],
        'history_verify'            => ['SCT'],
        'profil'                    => ['STF', 'KTA', 'KEP', 'SCT', 'ADM'],
        'leave_history'             => ['STF', 'KTA'],
        'proses_approval_cuti'      => ['KEP', 'KTA'],
        'laporan_cuti'              => ['KEP', 'KTA', 'ADM'],
        'user_add'                  => ['ADM'],
        'insert_user'               => ['ADM'],
        'user_edit'                 => ['ADM'],
        'process_user_update'       => ['ADM', 'KEP'],
        'edit_profil'               => ['STF', 'KTA', 'KEP'],
        'process_update_profil'     => ['STF', 'KTA', 'KEP'],
        'edit_password'             => ['STF', 'KTA', 'KEP', 'ADM'],
        'process_update_password'   => ['STF', 'KTA', 'KEP', 'ADM'],
        'user_reset_password'       => ['ADM'],
        'user_nonaktifkan'          => ['ADM']
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

        case 'user_reset_password':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->resetPassword();
            break;

        case 'user_nonaktifkan':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->nonaktifkanUser();
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

        case 'export_laporan_excel':
            require_once __DIR__ . '/app/controllers/LaporanController.php';
            $controller = new LaporanController($conn);
            $controller->exportLaporanPerizinanExcel();
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

        case 'verifikasi_non_perizinan':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyNonPerizinanList();
            break;

        case 'history_verify':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->historyVerify(); 
            break;

        case 'profil':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->userInfo();
            break;
        
        case 'tambah_non_perizinan':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyNonPerizinan();
            break;
        
        case 'verify_masuk_non_perizinan':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->verifyMasukNonPerizinan();
            break;

        case 'history_verify_non_perizinan':
            require_once __DIR__ . '/app/controllers/LogController.php';
            $controller = new LogController($conn);
            $controller->historyVerifyNonPerizinan(); 
            break;

        case 'ajukan_cuti':
            require_once __DIR__ . '/app/controllers/CutiController.php';
            $controller = new CutiController($conn);
            $controller->formAjukanCuti();
            break;

        case 'dashboard_cuti':
            require_once __DIR__ . '/app/views/cuti/dashboard_cuti.php';
            break;
        
        case 'insert_cuti':
            require_once __DIR__ . '/app/controllers/CutiController.php';
            $controller = new CutiController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->ajukanCuti();
            } else {
                $controller->formAjukanCuti();
            }
            break;

        case 'leave_history':
            require_once __DIR__ . '/app/controllers/CutiController.php';
            $controller = new CutiController($conn);
            $controller->leaveHistory();
            break;

        case 'daftar_pengajuan_cuti':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->daftar_pengajuan_cuti();
            break;

        case 'proses_approval_cuti':
            require_once __DIR__ . '/app/controllers/AtasanController.php';
            $controller = new AtasanController($conn);
            $controller->proses_approval_cuti();
            break;

        case 'laporan_cuti':
            require_once __DIR__ . '/app/controllers/LaporanController.php';
            $controller = new LaporanController($conn);
            $controller->laporanCuti();
            break;

        case 'export_laporan_cuti_excel':
            require_once __DIR__ . '/app/controllers/LaporanController.php';
            $controller = new LaporanController($conn);
            $controller->exportLaporanCutiExcel();
            break;
        
        case 'cetak_cuti':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once __DIR__ . '/app/controllers/CutiController.php';
                $controller = new CutiController($conn);
                $controller->cetakCuti();
            } else {
                require_once __DIR__ . '/app/controllers/LaporanController.php';
                $controller = new LaporanController($conn);
                $controller->laporanCuti();
            }
            break;

        case 'hapus_cuti':
            require_once __DIR__ . '/app/controllers/CutiController.php';
            $controller = new CutiController($conn);
            $controller->hapusCuti();
            break;

        case 'user_add':
            require_once __DIR__ . '/app/controllers/AdminController.php';
            $controller = new AdminController($conn);
            $controller->user_add();
            break;

        case 'insert_user':
            require_once __DIR__ . '/app/controllers/AdminController.php';
            $controller = new AdminController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->insert_user();
            } else {
                $controller->user_add();
            }
            break;

        case 'user_edit':
            require_once __DIR__ . '/app/controllers/AdminController.php';
            $controller = new AdminController($conn);
            $controller->editUser();
            break;

        case 'process_user_update':
            require_once __DIR__ . '/app/controllers/AdminController.php';
            $controller = new AdminController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processUserUpdate();
            } else {
                $controller->editUser();
            }
            break;

        case 'edit_profil':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->editProfil();
            break;

        case 'process_update_profil':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processUpdateProfil(); 
            } else {
                $controller->editProfil();
            }
            break;

        case 'edit_password':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            $controller->editPassword(); 
            break;

        case 'process_update_password':
            require_once __DIR__ . '/app/controllers/UserController.php';
            $controller = new UserController($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processUpdatePassword(); 
            } else {
                $controller->editPassword();
            }
            break;

        default:
            echo "Halaman tidak ditemukan!";
            break;
    }
}
?>
