<?php
session_start();

// Hancurkan semua session data
$_SESSION = [];
session_unset();
session_destroy();

// Redirect kembali ke login
header("Location: login.php");
exit;