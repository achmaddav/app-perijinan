<?php
/**
 * Konfigurasi session dan keamanan aplikasi.
 */

// Tentukan apakah koneksi menggunakan HTTPS
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

// Konfigurasi session cookie yang aman
session_set_cookie_params([
    'lifetime' => 0,         // Cookie hanya berlaku selama sesi browser aktif
    'path'     => '/',       // Berlaku untuk seluruh domain
    'domain'   => '',        // Sesuaikan jika menggunakan subdomain
    'secure'   => $secure,   // Hanya kirim cookie jika HTTPS digunakan
    'httponly' => true,      // Mencegah akses JavaScript ke cookie
    'samesite' => 'Strict'   // Mencegah pengiriman cookie lintas situs
]);

// Mulai session dengan regenerasi ID untuk meningkatkan keamanan
session_start();
session_regenerate_id(true);

/**
 * Tambahkan header keamanan untuk perlindungan tambahan.
 */
header("X-Frame-Options: SAMEORIGIN");     // Mencegah clickjacking
header("X-XSS-Protection: 1; mode=block"); // Mengaktifkan proteksi XSS pada browser
header("X-Content-Type-Options: nosniff"); // Mencegah MIME-type sniffing

/**
 * Sertakan file konfigurasi dan routing.
 */
// require_once '../config/database.php';
// require_once '../routes.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';


/**
 * Dapatkan koneksi database menggunakan Singleton Pattern.
 */
$database = Database::getInstance();
$conn = $database->getConnection();

/**
 * Jalankan routing aplikasi.
 */
route($conn);
?>
