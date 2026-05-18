<?php
session_start();
require 'koneksi.php';

// Jika ada session gmail, ubah status menjadi Offline dulu di database
if (isset($_SESSION['gmail'])) {
    $gmail = $_SESSION['gmail'];
    mysqli_query($koneksi, "UPDATE users SET status='Offline' WHERE gmail='$gmail'");
}

// Hancurkan semua session
session_unset();
session_destroy();

// Tendang kembali ke halaman utama login
header("Location: index.html");
exit;
?>