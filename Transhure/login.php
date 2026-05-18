<?php
session_start();
require 'koneksi.php';

if (isset($_POST['login'])) {
    $gmail_user = mysqli_real_escape_string($koneksi, $_POST['gmail']);
    $password = $_POST['password'];

    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_user'");

    if (mysqli_num_rows($cek_user) === 1) {
        $row = mysqli_fetch_assoc($cek_user);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['nama']; 
            $_SESSION['gmail'] = $row['gmail']; // Ditambahkan untuk pengenalan profil unik

            // UBAH STATUS MENJADI ONLINE DI DATABASE
            mysqli_query($koneksi, "UPDATE users SET status='Online' WHERE gmail='{$row['gmail']}'");
            
            echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Login Berhasil!',
                        text: 'Selamat datang kembali di Trashure.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'dashboard.php';
                    });
                });
            </script></body></html>";
            exit;
        } else {
            echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: 'Password salah!',
                        confirmButtonColor: '#224B3C'
                    }).then(() => {
                        window.location.href = 'index.html';
                    });
                });
            </script></body></html>";
            exit;
        }
    } else {
        echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gmail tidak ditemukan!',
                    confirmButtonColor: '#224B3C'
                }).then(() => {
                    window.location.href = 'index.html';
                });
            });
        </script></body></html>";
        exit;
    }
}
?>