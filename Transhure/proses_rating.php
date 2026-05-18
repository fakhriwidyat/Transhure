<?php
session_start();
include 'koneksi.php'; // Pastikan nama file koneksi kamu benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bintang = intval($_POST['rating']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    // Jika user sudah login, simpan namanya. Jika belum, simpan sebagai 'Anonim'
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonim';

    $query = "INSERT INTO ratings (username, bintang, pesan) VALUES ('$username', $bintang, '$pesan')";
    
    if (mysqli_query($conn, $query)) {
        echo 'sukses';
    } else {
        echo 'gagal';
    }
}
?>