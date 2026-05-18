<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

$username = $_SESSION['username'];

// MEMAKAI 'nama' KARENA DI DATABASE KAMU NAMANYA 'nama'
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE nama = '$username'");
$user_data = mysqli_fetch_assoc($query_user);

if(!$user_data) {
    die("Error: Data user dengan nama '$username' tidak ditemukan di database.");
}

// MEMAKAI 'id' KARENA DI DATABASE KAMU NAMANYA 'id'
$id_user = $user_data['id'];

$jawaban_user = json_decode($_POST['jawaban_user'], true);

$skor = 0;
$benar = 0;
$salah = 0;

foreach ($jawaban_user as $id_soal => $jawaban) {
    $query = mysqli_query($conn, "SELECT jawaban_benar FROM soal_quiz WHERE id_soal = '$id_soal'");
    $data = mysqli_fetch_assoc($query);
    
    if ($data['jawaban_benar'] == $jawaban) {
        $skor += 10;
        $benar++;
    } else {
        // HANYA MENGHITUNG JUMLAH SALAH, TIDAK ADA PENGURANGAN SKOR
        $salah++;
    }
}

// Update total_poin dan terakhir_quiz berdasarkan kolom 'nama'
mysqli_query($conn, "UPDATE users SET total_poin = total_poin + $skor, terakhir_quiz = CURDATE() WHERE nama = '$username'");

// Simpan ke riwayat_poin menggunakan id user
if ($id_user > 0) {
    $tanggal = date('Y-m-d');
    @mysqli_query($conn, "INSERT INTO riwayat_poin (id_user, tanggal, poin_didapat) VALUES ('$id_user', '$tanggal', '$skor')");
}

header("Location: hasil_quiz.php?benar=$benar&salah=$salah&poin=$skor");
exit;
?>