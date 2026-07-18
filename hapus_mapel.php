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
    
    // Data Loss Prevention: Kosongkan referensi mapel di jadwal agar slot tetap ada
    mysqli_query($db, "UPDATE jadwal SET id_mapel = NULL WHERE id_mapel = '$id'");
    
    $delete = mysqli_query($db, "DELETE FROM mapel WHERE id = '$id'");
    
    if (!$delete) {
        echo "<script>alert('Gagal menghapus data mapel: " . addslashes(mysqli_error($db)) . "'); window.location='mapel.php';</script>";
        exit;
    }
}

header("Location: mapel.php");
exit;
?>