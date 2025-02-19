<?php
session_start();
$message = $_GET['message'] ?? 'Terjadi kesalahan.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="text-center p-4 bg-white shadow rounded-3">
        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
        <h4 class="fw-bold text-danger">Akses Ditolak</h4>
        <p class="text-muted"><?php echo htmlspecialchars($message); ?></p>
        <a href="/app-perijinan/dashboard" class="btn btn-outline-danger px-4">Kembali ke Dashboard</a>
    </div>
</body>
</html>
