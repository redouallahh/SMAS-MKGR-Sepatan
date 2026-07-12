<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Cek apakah ada ID yang dikirim
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // Cek id_user dulu sebelum dihapus
    $q = mysqli_query($db, "SELECT id_user FROM guru WHERE id = '$id'");
    $guru = mysqli_fetch_assoc($q);
    $id_user = $guru['id_user'] ?? null;

    // Data Loss Prevention: Cek apakah guru punya jadwal
    $cek_jadwal = mysqli_query($db, "SELECT id FROM jadwal WHERE id_guru = '$id'");
    if (mysqli_num_rows($cek_jadwal) > 0) {
        echo "<script>alert('Gagal menghapus! Guru ini masih memiliki jadwal yang aktif. Silakan hapus jadwalnya terlebih dahulu untuk mencegah kehilangan data (Data Loss Prevention).'); window.location='guru.php';</script>";
        exit;
    }

    // Jalankan query hapus asli ke database
    $delete = mysqli_query($db, "DELETE FROM guru WHERE id = '$id'");
    
    if (!$delete) {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($db)) . "'); window.location='guru.php';</script>";
        exit;
    }
    
    // Hapus akun login jika ada
    if ($id_user) {
        mysqli_query($db, "DELETE FROM user WHERE id = '$id_user'");
    }
}

// Setelah sukses menghapus, langsung lempar balik ke halaman utama guru
header("Location: guru.php");
exit;
?>