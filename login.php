<?php
session_start();
require 'koneksi.php';

// Pastikan tombol di halaman login (index.html) memiliki name="login"
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek_user) === 1) {
        $row = mysqli_fetch_assoc($cek_user);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            // Diubah menjadi $row['nama'] agar dashboard memunculkan nama lengkap
            $_SESSION['username'] = $row['nama']; 
            
            // PERBAIKAN: Diarahkan ke dashboard.php
            echo "<script>alert('Berhasil masuk!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Password salah!'); window.location='index.html';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location='index.html';</script>";
    }
}
?>