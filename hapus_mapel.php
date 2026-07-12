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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Data Loss Prevention: Cek apakah mapel punya jadwal
    $cek_jadwal = mysqli_query($db, "SELECT id FROM jadwal WHERE id_mapel = '$id'");
    if (mysqli_num_rows($cek_jadwal) > 0) {
        echo "<script>alert('Gagal menghapus! Mata Pelajaran ini masih digunakan dalam jadwal yang aktif. Silakan hapus jadwalnya terlebih dahulu (Data Loss Prevention).'); window.location='mapel.php';</script>";
        exit;
    }
    
    $delete = mysqli_query($db, "DELETE FROM mapel WHERE id = '$id'");
    
    if (!$delete) {
        echo "<script>alert('Gagal menghapus data mapel: " . addslashes(mysqli_error($db)) . "'); window.location='mapel.php';</script>";
        exit;
    }
}

header("Location: mapel.php");
exit;
?>