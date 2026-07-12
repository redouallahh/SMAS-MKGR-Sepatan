<?php
// Fungsi parse .env dengan stdlib PHP (Ponytail way)
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $env = parse_ini_file($env_path);
    foreach ($env as $key => $val) {
        putenv("$key=$val");
    }
}

$host     = getenv('DB_HOST') ?: "127.0.0.1";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$database = getenv('DB_NAME') ?: "jadwal-mkgr-edoy-main";

// Menggunakan koneksi MySQLi Procedural agar serasi dengan mysqli_query
$db = mysqli_connect($host, $username, $password, $database);

// Cek apakah koneksi berhasil atau gagal
if (!$db) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>