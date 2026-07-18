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

    // Data Loss Prevention: Kosongkan referensi kelas di jadwal agar slot tetap ada
    mysqli_query($db, "UPDATE jadwal SET id_kelas = NULL WHERE id_kelas = '$id'");

    $delete = mysqli_query($db, "DELETE FROM kelas WHERE id = '$id'");
}

header("Location: kelas.php");
exit;
?>