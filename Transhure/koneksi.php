<?php
$host = "localhost";
$user = "root";      
$pass = "";           
$db   = "trashure_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// --- INI JEMBATANNYA ---
// Agar kode kuis yang mencari "$conn" otomatis terhubung ke "$koneksi"
$conn = $koneksi; 

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>