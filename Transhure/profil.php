<?php
session_start();
require 'koneksi.php';

// Proteksi halaman: Jika belum login, tendang ke halaman login
if (!isset($_SESSION['login']) || !isset($_SESSION['gmail'])) {
    header("Location: index.html");
    exit;
}

$gmail_login = $_SESSION['gmail']; // Gmail milik user yang sedang log in

// 1. CEK PARAMETER URL: Apakah sedang melihat profil sendiri atau orang lain?
if (isset($_GET['gmail'])) {
    $gmail_target = mysqli_real_escape_string($koneksi, $_GET['gmail']);
} else {
    $gmail_target = $gmail_login; // Jika tidak ada parameter di URL, default ke profil sendiri
}

// 2. TARIK DATA USER TARGET DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_target'");

// Jika akun yang dicari di URL tidak ada, kembalikan ke profil sendiri
if (mysqli_num_rows($query) === 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE gmail='$gmail_login'");
    $gmail_target = $gmail_login;
}

$user = mysqli_fetch_assoc($query);

// 3. LOGIKA INDIKATOR: Apakah ini profil milik sendiri? (Hasilnya berupa true/false)
$is_own_profile = ($gmail_target === $gmail_login);

// Format tanggal bergabung real
$tanggal = date('d F Y', strtotime($user['tanggal_daftar']));
$bulan_inggris = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$bulan_indo = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$tanggal_real = str_replace($bulan_inggris, $bulan_indo, $tanggal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - TRASHURE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="pr.css">
</head>
<body>

    <div class="profile-container">
        <div class="profile-icon"><i class="fas fa-user-circle"></i></div>
        <div class="username"><?= htmlspecialchars($user['nama']); ?></div>
        
        <div class="info-box">
            <span class="info-label">Status Akun:</span>
            <span class="<?= ($user['status'] == 'Online') ? 'status-online' : 'status-offline'; ?>">
                <i class="fas fa-circle" style="font-size: 10px; margin-right: 5px;"></i><?= $user['status']; ?>
            </span>
        </div>

        <div class="info-box">
            <span class="info-label">Total Poin:</span>
            <span><?= $user['total_poin']; ?> Pts</span>
        </div>

        <div class="info-box">
            <span class="info-label">Member Sejak:</span>
            <span><?= $tanggal_real; ?></span>
        </div>

        <div class="info-box">
            <span class="info-label">Gmail:</span>
            <span>
                <?= $is_own_profile ? htmlspecialchars($user['gmail']) : '<i class="fas fa-eye-slash" style="color: #999;"></i> Privat'; ?>
            </span>
        </div>

        <div class="info-box">
            <span class="info-label">Password:</span>
            <span>
                <?= $is_own_profile ? '•••••••• <i class="fas fa-lock" style="font-size: 12px; color: #777; margin-left: 5px;"></i>' : '<i class="fas fa-eye-slash" style="color: #999;"></i> Privat'; ?>
            </span>
        </div>

        <a href="javascript:history.back()" class="btn-kembali">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
        </a>
    </div>

</body>
</html>