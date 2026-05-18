<?php
session_start();
include 'koneksi.php';

// Menghubungkan variabel internal dengan session agar navbar mendeteksi user aktif
$is_logged_in = isset($_SESSION['username']);
$nama_user = $is_logged_in ? $_SESSION['username'] : '';

// AMBIL POIN USER YANG SEDANG LOGIN UNTUK DITAMPILKAN DI DROPDOWN
$poin_aktif = 0;
if ($is_logged_in) {
    $query_aktif = mysqli_query($conn, "SELECT total_poin FROM users WHERE nama = '$nama_user'");
    if ($row_aktif = mysqli_fetch_assoc($query_aktif)) {
        $poin_aktif = $row_aktif['total_poin'];
    }
}

// ==============================================
// LOGIKA SORTING (TERATAS / TERBAWAH)
// ==============================================
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc'; 
$order_sql = ($sort === 'asc') ? 'ASC' : 'DESC'; 

// Mengambil data peringkat 10 besar pemain sesuai sorting (DITAMBAHKAN GMAIL)
$query = mysqli_query($conn, "SELECT nama, gmail, total_poin FROM users ORDER BY total_poin $order_sql LIMIT 10");

// SIMPAN DATA QUERY KE ARRAY
$leaderboard_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $leaderboard_data[] = $row;
}

function tentukanTingkatRank($poin) {
    if ($poin >= 200) return 'Emas';
    if ($poin >= 100) return 'Perak';
    return 'Perunggu';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - Trashure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="leaderboard.css?v=3.0"> 
    <style>
        /* Efek smooth scrolling agar saat turun ke grafik terlihat mulus */
        html {
            scroll-behavior: smooth;
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="mulai_quiz.php">Point</a></li>
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

    <main class="main-content">
        <div class="leaderboard-wrapper">
            
            <div class="header-panel">
                <div class="header-left">
                    <select class="status-badge-select" onchange="window.location.href='?sort='+this.value">
                        <option value="desc" <?php if($sort == 'desc') echo 'selected'; ?>>USER TERATAS</option>
                        <option value="asc" <?php if($sort == 'asc') echo 'selected'; ?>>USER TERBAWAH</option>
                    </select>
                    
                    <div class="header-desc">
                        <?php echo ($sort === 'asc') ? 'User dengan perolehan point paling sedikit' : 'User dengan perolehan point terbanyak'; ?>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="main-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">No</th>
                            <th style="width: 12%;">Profil</th>
                            <th>Nama Pengguna</th>
                            <th>Point</th>
                            <th>Rank</th>
                            <th style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($leaderboard_data as $row) { 
                            $rank_user = tentukanTingkatRank($row['total_poin']);
                        ?>
                        <tr>
                            <td class="rank-number"><?php echo $no++; ?></td>
                            <td><div class="avatar-circle">👤</div></td>
                            <td style="font-weight: 600; text-align: left; padding-left: 40px;">
                                <?php echo htmlspecialchars($row['nama']); ?>
                            </td>
                            <td style="font-weight: bold;"><?php echo $row['total_poin']; ?></td>
                            <td style="font-weight: 500;"><?php echo $rank_user; ?></td>
                            <td><a href="profil.php?gmail=<?php echo urlencode($row['gmail']); ?>" class="btn-action-profile">Profil</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div id="diagram-poin" class="chart-container" style="width: 92%; max-width: 800px; margin: 40px auto 30px; background: rgba(13, 71, 58, 0.9); padding: 20px 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); box-sizing: border-box; scroll-margin-top: 100px;">
                <h3 style="color: white; text-align: center; margin-bottom: 15px; font-family: sans-serif; font-size: 20px;">Statistik Perolehan Poin</h3>
                <canvas id="statistikLeaderboard"></canvas>
            </div>

        </div>
    </main>
    
    
<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // ========================================================
    // LOGIKA DIAGRAM STATISTIKA (CHART.JS)
    // ========================================================
    document.addEventListener("DOMContentLoaded", function() {
        let namaUser = <?php echo json_encode(array_column($leaderboard_data, 'nama')); ?>;
        let poinUser = <?php echo json_encode(array_column($leaderboard_data, 'total_poin')); ?>;

        const ctx = document.getElementById('statistikLeaderboard').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: namaUser,
                datasets: [{
                    label: 'Total Point',
                    data: poinUser,
                    backgroundColor: '#15A38F', 
                    borderColor: '#ffffff',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.3, 
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 100, 
                        ticks: { color: 'white', precision: 0 },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: { color: 'white' },
                        grid: { display: false }
                    }
                }
            }
        });
    });

    // ==========================================
    // FUNGSI LAINNYA (DROPDOWN & LOGOUT)
    // ==========================================
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.classList.toggle("show");
        if(dropdown.classList.contains("show")){
            dropdown.style.visibility = "visible";
            dropdown.style.opacity = "1";
            dropdown.style.transform = "translateY(0)";
        } else {
            dropdown.style.visibility = "hidden";
            dropdown.style.opacity = "0";
            dropdown.style.transform = "translateY(-15px)";
        }
    }

    window.onclick = function(event) {
        if (!event.target.closest('.profile-wrapper')) {
            const dropdown = document.getElementById("profileDropdown");
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                dropdown.style.visibility = "hidden";
                dropdown.style.opacity = "0";
                dropdown.style.transform = "translateY(-15px)";
            }
        }
    }

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
</html>`~