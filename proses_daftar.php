<?php
// Panggil koneksi ke database
require 'koneksi.php';

// Pastikan tombol daftar sudah ditekan dari form
if (isset($_POST['daftar'])) {
    // Tangkap data yang diisi user. Ubah username menjadi gmail sesuai dengan input HTML
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $gmail_user = mysqli_real_escape_string($koneksi, $_POST['gmail']); 
    $password = $_POST['password'];

    // Enkripsi password biar aman (berubah jadi kode acak)
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah gmail sudah ada di database. (Pastikan nama kolom di tabelmu adalah 'gmail')
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_user'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Jika gmail sudah dipakai orang lain
        echo "<script>alert('Gmail sudah terdaftar! Gunakan gmail lain.'); window.location='sign_up.html';</script>";
    } else {
        // Jika gmail tersedia, simpan ke database. (Pastikan nama kolom di tabelmu adalah 'nama', 'gmail', 'password')
        $query = "INSERT INTO users (nama, gmail, password) VALUES ('$nama', '$gmail_user', '$password_hashed')";
        
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