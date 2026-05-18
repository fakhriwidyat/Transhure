<?php
// Panggil koneksi ke database
require 'koneksi.php';

// Pastikan tombol daftar sudah ditekan dari form
if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $gmail_user = mysqli_real_escape_string($koneksi, $_POST['gmail']); 
    $password = $_POST['password'];

    // Enkripsi password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah gmail sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_user'");
    
    if (mysqli_num_rows($cek) > 0) {
        // POP-UP GMAIL SUDAH TERDAFTAR
        echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body style='background-color: #f4f7f6;'>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gmail Sudah Terdaftar',
                    text: 'Gunakan gmail lain atau silakan login.',
                    confirmButtonColor: '#224B3C'
                }).then(() => {
                    window.location.href = 'sign_up.html';
                });
            });
        </script></body></html>";
        exit;
        
    } else {
        // Simpan ke database
        $query = "INSERT INTO users (nama, gmail, password, total_poin) VALUES ('$nama', '$gmail_user', '$password_hashed', 0)";
        
        if (mysqli_query($koneksi, $query)) {
            // POP-UP DAFTAR BERHASIL (IKON ORANYE SEPERTI LOGOUT)
            echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body style='background-color: #f4f7f6;'>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning', 
                        title: 'Daftar Berhasil!',
                        text: 'Akun Trashure kamu sudah siap. Silakan masuk!',
                        confirmButtonText: 'Mulai Login',
                        confirmButtonColor: '#1bc38d', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.html'; 
                        }
                    });
                });
            </script></body></html>";
            exit;
            
        } else {
            // POP-UP GAGAL SISTEM
            echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body style='background-color: #f4f7f6;'>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mendaftar!',
                        text: 'Terjadi kesalahan pada sistem database.',
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        window.location.href = 'sign_up.html';
                    });
                });
            </script></body></html>";
            exit;
        }
    }
}
?>