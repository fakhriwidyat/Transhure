<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

$username = $_SESSION['username'];
$benar = isset($_GET['benar']) ? $_GET['benar'] : 0;
$salah = isset($_GET['salah']) ? $_GET['salah'] : 0;
$poin_baru = isset($_GET['poin']) ? $_GET['poin'] : 0;

// Ambil total poin menggunakan kolom 'nama' sesuai database
$query = mysqli_query($conn, "SELECT total_poin FROM users WHERE nama = '$username'");
$user_data = mysqli_fetch_assoc($query);
$total_keseluruhan = isset($user_data['total_poin']) ? $user_data['total_poin'] : 0;

// FUNGSI UNTUK MENGATUR GAMBAR MEDALI DAN TEKS RANK
function dapatkanRankDanMedali($poin) {
    if ($poin >= 200) {
        return ['nama_rank' => 'Emas', 'gambar' => 'medaliemas.png'];
    }
    if ($poin >= 100) {
        return ['nama_rank' => 'Perak', 'gambar' => 'medaliperak.png'];
    }
    return ['nama_rank' => 'Perunggu', 'gambar' => 'medaliperunggu.png'];
}

$data_rank = dapatkanRankDanMedali($total_keseluruhan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Kuis - Trashure</title>
    <link rel="stylesheet" href="hasil.css?v=1.1">
</head>
<body>
    <div class="result-card">
        <h2>Selesai!</h2>
        <div class="score">+ <?php echo $poin_baru; ?> Poin</div>
        
        <div class="stats">
            <span class="benar">✔ Benar: <?php echo $benar; ?></span>
            <span class="salah">✖ Salah: <?php echo $salah; ?></span>
        </div>

        <div class="rank-box">
            Rank Saat Ini: <br> 
            <span class="rank-title">
                <?php echo $data_rank['nama_rank']; ?> 
                <img src="<?php echo $data_rank['gambar']; ?>" alt="Medali" class="medali-img">
            </span>
            <small>Total Poin: <?php echo $total_keseluruhan; ?></small>
        </div>

        <a href="leaderboard.php" class="btn-link">Lihat Leaderboard</a>
        <a href="dashboard.php" class="btn-link" style="background-color: #15A38F;">Kembali ke Beranda</a>
    </div>
</body>
</html>