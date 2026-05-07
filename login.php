<?php
session_start();
require 'koneksi.php';

// Pastikan tombol di halaman login (index.html) memiliki name="login"
if (isset($_POST['login'])) {
    // TANGKAP GMAIL: Ubah $_POST['username'] jadi $_POST['gmail']
    $gmail_user = mysqli_real_escape_string($koneksi, $_POST['gmail']);
    $password = $_POST['password'];

    // CARI DI DATABASE: Ubah WHERE username= menjadi WHERE gmail=
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_user'");

    if (mysqli_num_rows($cek_user) === 1) {
        $row = mysqli_fetch_assoc($cek_user);
        
        // Verifikasi kecocokan password
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            // Diambil dari kolom 'nama' agar dashboard memunculkan nama lengkap
            $_SESSION['username'] = $row['nama']; 
            
            // Diarahkan ke dashboard.php
            echo "<script>alert('Berhasil masuk!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Password salah!'); window.location='index.html';</script>";
        }
    } else {
        // Pesan error disesuaikan
        echo "<script>alert('Gmail tidak ditemukan!'); window.location='index.html';</script>";
    }
}
?>