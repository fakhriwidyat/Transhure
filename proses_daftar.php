<?php
// Panggil koneksi ke database
require 'koneksi.php';

// Pastikan tombol daftar sudah ditekan dari form
if (isset($_POST['daftar'])) {
    // Tangkap data yang diisi user
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Enkripsi password biar aman (berubah jadi kode acak)
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada di database
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Jika username sudah dipakai orang lain
        echo "<script>alert('Username sudah dipakai! Pilih yang lain.'); window.location='sign_up.html';</script>";
    } else {
        // Jika username tersedia, simpan ke database
        $query = "INSERT INTO users (nama, username, password) VALUES ('$nama', '$username', '$password_hashed')";
        
        if (mysqli_query($koneksi, $query)) {
            // Jika berhasil disimpan, muncul pop-up dan arahkan ke halaman login
            echo "<script>alert('Daftar berhasil! Silakan masuk.'); window.location='index.html';</script>";
        } else {
            // Jika sistem database error
            echo "<script>alert('Gagal mendaftar!'); window.location='sign_up.html';</script>";
        }
    }
}
?>