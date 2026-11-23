<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header("Location: views/presensi/rekap.php");
} else {
    header("Location: views/auth/login.php");
}
exit;