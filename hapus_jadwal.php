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

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    // Query Hapus standar
    mysqli_query($db, "DELETE FROM jadwal WHERE id = '$id'");
}
header("Location: jadwal.php");
exit;
?>