<?php
session_start();

// Proteksi Halaman: Wajib login
if (!isset($_SESSION['username'])) { 
    header("Location: index.html"); 
    exit; 
}

$nama_user = $_SESSION['username'];

// Ambil poin user yang login untuk ditampilkan di dropdown navbar
include 'koneksi.php'; 
$poin_aktif = 0;
$query_poin = mysqli_query($conn, "SELECT total_poin FROM users WHERE nama = '$nama_user'");
if ($row_poin = mysqli_fetch_assoc($query_poin)) {
    $poin_aktif = $row_poin['total_poin'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Quiz - Trashure</title>
    
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="mulai.css?v=1.2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Memastikan kotak kuis ada di tengah, tidak tertutup navbar/footer */
        .quiz-page-content {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            box-sizing: border-box;
        }
    </style>
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
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
            <div class="nav-right">
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
            </div>
        </div>
    </nav>

    <main class="quiz-page-content">
        <div class="quiz-container">
            <img src="quiz_coin.png" alt="Quiz" style="width: 150px; margin-bottom: 20px;">
            <h2>Daily Quiz Trashure</h2>
            <p>Jawab 10 soal acak tentang lingkungan.<br>Benar (+10 Poin) &nbsp;|&nbsp; Salah (-10 Poin)</p>
            
            <a href="#" class="btn-mulai" onclick="tampilkanModal(event)">Mulai Kerjakan</a>
        </div>
    </main>

    <div id="modalKonfirmasi" class="modal-overlay">
        <div class="modal-content">
            <h3>Konfirmasi</h3>
            <p>Yakin ingin mulai mengerjakan kuis sekarang?</p>
            <div class="modal-buttons">
                <button class="btn-kembali-modal" onclick="tutupModal()">Kembali</button>
                <a href="quiz.php" class="btn-lanjut-modal">Lanjut</a>
            </div>
        </div>
    </div>

    
<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Script Modal Kuis
        function tampilkanModal(event) {
            event.preventDefault(); 
            document.getElementById('modalKonfirmasi').style.display = 'flex'; 
        }

        function tutupModal() {
            document.getElementById('modalKonfirmasi').style.display = 'none'; 
        }

        // Script Dropdown Profil
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

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

        // Script Logout Pop-up
        function konfirmasiLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Keluar dari Trashure?',
                text: "Sesi kamu akan diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#15A38F',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>
</body>
</html>