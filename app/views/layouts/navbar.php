<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand-lg bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Toggle Sidebar -->
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link text-dark" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- User Dropdown -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> <?php echo $nama; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="index.php?page=profil"><i class="fas fa-user me-2"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="index.php?page=logout" class="btn btn-danger">Ya, Logout</a>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer .btn {
        transition: background 0.3s ease;
    }

    .modal-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
