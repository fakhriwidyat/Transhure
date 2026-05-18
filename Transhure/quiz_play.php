<?php
session_start();
include 'koneksi.php'; // File koneksi database kamu

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// Ambil 10 soal secara ACAK dari database
$query = "SELECT id_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d FROM soal_quiz ORDER BY RAND() LIMIT 10";
$result = mysqli_query($conn, $query);

$daftar_soal = [];
while ($row = mysqli_fetch_assoc($result)) {
    $daftar_soal[] = $row;
}

// Ubah ke format JSON agar bisa dibaca oleh JavaScript
$json_soal = json_encode($daftar_soal);
?>

<script>
    const soalQuiz = <?php echo $json_soal; ?>;
    let soalSekarang = 0;
    // Logika JS untuk menampilkan array soalQuiz[soalSekarang] ke layar HTML
    // ...
</script>