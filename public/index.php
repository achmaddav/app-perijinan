<?php
session_start();
session_regenerate_id(true);

require_once '../config/database.php';
require_once '../routes.php';

// Gunakan Singleton Pattern untuk mendapatkan koneksi
$database = Database::getInstance();
$conn = $database->getConnection();

// Jalankan routing
route($conn);
?>
