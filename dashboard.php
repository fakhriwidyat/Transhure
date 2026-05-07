<?php
session_start();

// Proteksi halaman: Jika belum login, tendang balik ke halaman login (index.html)
if (!isset($_SESSION['login'])) {
    header("Location: index.html");
    exit;
}

// Ambil nama user yang tadi disimpan saat login
$nama_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TRASHURE</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="logo.png" alt="TRASHURE"> 
            </div>
            <ul class="nav-links">
                <li><a href="peta.php">Peta</a></li>
                <li><a href="scanner.php">Scan Sampah</a></li>
                <li><a href="leaderboard.php">Leaderboard</a></li>
                <li><a href="quiz.php">Point</a></li>
            </ul>
            <div class="nav-right">
                <div class="user-profile">
                    <span><?php echo htmlspecialchars($nama_user); ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
                
                <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar dari Trashure?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="greeting-box">
                selamat Datang di Trashure, <?php echo htmlspecialchars($nama_user); ?>!
            </div>
            
            <div class="hero-title-container">
                <h1>
                    <span class="banner-box box-1">MENGELOLA</span>
                    <span class="banner-box box-2">SAMPAH</span>
                </h1>
            </div>
            <h2>Membangun Masa depan</h2>
            
            <img src="bumi.png" alt="Bumi" class="earth-img">
        </div>

        <div class="points-panel">
            <h3>Dapatkan Point</h3>
            <div class="point-item">
                <p>Daily Quiz <span>+10 Poin ></span></p>
            </div>
            <p class="point-note">*Dapatkan Point Anda</p>
        </div>
    </section>

    <section class="features-grid">
        <a href="scanner.php" class="card-small">
            <h4>Scanner Sampah</h4>
            <img src="qr-code.png" alt="QR">
        </a>

        <a href="peta.php" class="card-large">
            <div class="card-text">
                <h4>sebidang peta</h4>
                <img src="petakcl.png" alt="Map">
            </div>
            <div class="map-placeholder">
                Disini Gambar petanya ya
            </div>
        </a>

        <a href="statistik.php" class="card-small">
            <h4>Statistik</h4>
            <img src="" alt="Grafik">
        </a>
    </section>

    <footer>
        <div class="footer-logo">
            <img src="logo.png" alt="Trashure">
            <p>Trashure © Copyright all right reserved.</p>
        </div>
        <div class="footer-links">
            <div class="col">
                <h5>Product</h5>
                <p>Pusat daur ulang</p>
                <p>Pasar Organik</p>
                <p>Bank Sampah</p>
            </div>
            <div class="col">
                <h5>Company</h5>
                <p>About us</p>
            </div>
            <div class="col">
                <h5>Legal</h5>
                <p>Contact</p>
                <p>Rating</p>
            </div>
        </div>
    </footer>

</body>
</html>