<?php
session_start();

// Cek apakah user sedang login
$is_logged_in = isset($_SESSION['username']); 

// Tentukan nama: Jika login pakai username, jika tidak pakai "User"
$nama_user = $is_logged_in ? $_SESSION['username'] : "User";

// AMBIL POIN USER YANG SEDANG LOGIN
$poin_aktif = 0;
if ($is_logged_in) {
    include 'koneksi.php'; // Panggil koneksi database
    $query_poin = mysqli_query($conn, "SELECT total_poin FROM users WHERE nama = '$nama_user'");
    if ($row_poin = mysqli_fetch_assoc($query_poin)) {
        $poin_aktif = $row_poin['total_poin'];
    }
}
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
                <li><a href="peta.php" onclick="cekLogin(event, 'peta.php')">Peta</a></li>
                <li><a href="scanner.php" onclick="cekLogin(event, 'scanner.php')">Scan Sampah</a></li>
                <li><a href="leaderboard.php" onclick="cekLogin(event, 'leaderboard.php')">Leaderboard</a></li>
                <li><a href="mulai_quiz.php" onclick="cekLogin(event, 'mulai_quiz.php')">Point</a></li>
            </ul>
            <div class="nav-right">
                
                <?php if ($is_logged_in) : ?>
                    <div class="profile-wrapper">
                        <div class="user-profile" onclick="toggleDropdown()" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <span><?php echo htmlspecialchars($nama_user); ?></span>
                            <i class="fas fa-user-circle"></i>
                        </div>

                        <div id="profileDropdown" class="dropdown-content">
                            <div class="dropdown-header">
                                <i class="fas fa-user-circle fa-3x" style="color: #3d7a6a; margin-bottom: 10px;"></i>
                                <h4 style="margin: 0; color: #333;"><?php echo htmlspecialchars($nama_user); ?></h4>
                                <p style="margin: 5px 0 0; color: #15A38F; font-weight: bold; font-size: 14px;">Aktif | <?php echo $poin_aktif; ?> Pts</p>
                            </div>
                            <hr style="border: 0; border-top: 1px solid #ddd; margin: 0;">
                            <a href="profil.php" class="menu-link">
                                <i class="fas fa-user"></i> Lihat Profil Lengkap
                            </a>
                        </div>
                    </div>
                    
                    <a href="#" class="btn-logout" onclick="konfirmasiLogout(event)">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>

                <?php else : ?>
                    <a href="index.html" class="btn-login">
                        Masuk / Daftar <i class="fas fa-user-circle"></i>
                    </a>
                <?php endif; ?>
                
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="greeting-box">
                Selamat Datang di Trashure, <?php echo htmlspecialchars($nama_user); ?>!
            </div>
            
            <div class="hero-title-container">
                <h1>
                    <span class="banner-box box-1">MENGELOLA</span>
                    <span class="banner-box box-2">SAMPAH</span>
                </h1>
            </div>
            <h2>Membangun Masa Depan</h2>
            
            <img src="bumi.png" alt="Bumi" class="earth-img">
        </div>

        <div class="points-panel">
            <h3>Dapatkan Point</h3>
            <a href="mulai_quiz.php" onclick="cekLogin(event, 'mulai_quiz.php')" style="text-decoration: none; color: inherit; display: block;">
                <div class="point-item" style="cursor: pointer; transition: 0.3s;">
                    <p>
                        <img src="coin.png" class="coin-icon-small"> 
                        Daily Quiz <span>+10 Poin ></span>
                    </p>
                </div>
            </a>
             <p class="point-note">*Dapatkan Point Anda</p>
        </div>
    </section>

    <section class="features-grid">
        <a href="scanner.php" onclick="cekLogin(event, 'scanner.php')" class="card-small">
            <h4>Scanner Sampah</h4>
            <img src="scan.png" alt="QR">
        </a>

        <div class="card-large">
            <div class="card-text">
                <h4>sebidang peta</h4>
                <img src="petakcl.png" alt="Map">
            </div>
            
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d31653.63004314144!2d109.21921316694667!3d-7.420455856403247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1stempat%20pembuangan%20sampah%20di%20purwokerto!5e0!3m2!1sid!2sid!4v1715777000000!5m2!1sid!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                 Bled
                </iframe>
                
                <a href="https://www.google.com/maps/search/tempat+pembuangan+sampah+di+purwokerto" 
                onclick="cekLogin(event, 'https://www.google.com/maps/search/tempat+pembuangan+sampah+di+purwokerto')" 
                target="_blank" 
                class="open-map-btn">
                    Buka Peta di Halaman Baru ↗
                </a>
            </div>
        </div> 
        
        <a href="leaderboard.php#diagram-poin" onclick="cekLogin(event, 'leaderboard.php#diagram-poin')" class="card-small">
            <h3>Statistik</h3>
            <img src="statistik.png" alt="Statistik">
        </a>
    </section>

  
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. TANGKAP STATUS LOGIN DARI PHP
        const isUserLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;

        // 2. FUNGSI CEK LOGIN
        function cekLogin(event, url) {
            if (!isUserLoggedIn) {
                event.preventDefault(); // Hentikan fungsi klik sementara
                
                // Tampilkan Pop-Up Peringatan SweetAlert
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Ditolak!',
                    text: 'Kamu harus login atau daftar terlebih dahulu untuk menggunakan fitur ini.',
                    confirmButtonText: 'Masuk / Daftar Sekarang',
                    confirmButtonColor: '#1bc38d', // Sesuaikan warna hijau Trashure
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    cancelButtonColor: '#d33',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.html'; // Pindah ke halaman login
                    }
                });
            }
        }

        // Fungsi untuk buka/tutup menu profil (Asli bawaanmu)
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        // Fungsi otomatis nutup menu kalau user ngeklik di tempat lain (Asli bawaanmu)
        window.onclick = function(event) {
            if (!event.target.closest('.profile-wrapper')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
    <script src="dashboard.js"></script>
</body>
</html>