<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = '/aplikasi-presensi';

// Proteksi semua halaman selain login.php
if (!isset($is_login_page) && !isset($_SESSION['id_user'])) {
    header("Location: $base_url/views/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi RFID - SMK TI Airlangga Samarinda</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css">
</head>
<body>