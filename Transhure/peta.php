<?php
session_start();
$is_logged_in = isset($_SESSION['username']); 
$nama_user = $is_logged_in ? $_SESSION['username'] : "User";

// KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "trashure_db");
if (!$conn) { die("Koneksi database gagal: " . mysqli_connect_error()); }

// AMBIL POIN USER YANG SEDANG LOGIN
$poin_aktif = 0;
if ($is_logged_in) {
    $query_poin = mysqli_query($conn, "SELECT total_poin FROM users WHERE nama = '$nama_user'");
    if ($row_poin = mysqli_fetch_assoc($query_poin)) {
        $poin_aktif = $row_poin['total_poin'];
    }
}

// AMBIL DATA DARI DATABASE
$sql = "SELECT * FROM lokasi_sampah";
$result = mysqli_query($conn, $sql);

$data_lokasi = [];
if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $data_lokasi[$row['id']] = $row;
    }
}
$json_lokasi = json_encode($data_lokasi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sampah - Trashure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <link rel="stylesheet" href="peta.css">

    <style>
        /* Agar peta memuat penuh di dalam kotak */
        #map { width: 100%; height: 100%; border-radius: 12px; z-index: 1; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="logo.png" alt="TRASHURE" style="height: 45px;"> 
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="scanner.php">Scan Sampah</a></li>
                <li><a href="leaderboard.php">Leaderboard</a></li>
                <li><a href="quiz.php">Point</a></li>
            </ul>
            <div class="nav-right">
                <?php if ($is_logged_in) : ?>
                    <div class="profile-wrapper" style="position: relative; display: inline-block;">
                        <div class="user-profile" onclick="toggleDropdown()" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <span><?php echo htmlspecialchars($nama_user); ?></span>
                            <i class="fas fa-user-circle"></i>
                        </div>

                        <div id="profileDropdown" class="dropdown-content" style="position: absolute; top: 55px; right: 0; background-color: #ffffff; min-width: 220px; box-shadow: 0px 8px 20px #8b9b97; border-radius: 15px; z-index: 9999; overflow: hidden; visibility: hidden; opacity: 0; transform: translateY(-15px); transition: all 0.3s ease-in-out;">
                            <div class="dropdown-header" style="padding: 20px; background-color: #ecf3f1; text-align: center;">
                                <i class="fas fa-user-circle fa-3x" style="color: #3d7a6a; margin-bottom: 10px;"></i>
                                <h4 style="margin: 0; color: #333;"><?php echo htmlspecialchars($nama_user); ?></h4>
                                <p style="margin: 5px 0 0; color: #15A38F; font-weight: bold; font-size: 14px;">Aktif | <?php echo $poin_aktif; ?> Pts</p>
                            </div>
                            <hr style="border: 0; border-top: 1px solid #ddd; margin: 0;">
                            <a href="profil.php" class="menu-link" style="display: block; padding: 15px; color: #333; text-decoration: none; text-align: center; font-size: 14px; font-weight: bold; transition: background 0.3s;">
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

    <main class="map-section">
        <div class="map-glow-container">
            <div id="map"></div>
        </div>

        <div class="panels-container">
            <div class="panel detail-panel-only">
                <div class="panel-header">
                    <i class="fas fa-map-marker-alt" style="color: red;"></i> DETAIL LOKASI
                </div>
                <div class="panel-body detail-body" id="tempat-detail">
                    <h4 style="text-align: center; color: #555; margin-top: 15px; font-weight: normal;">
                        Silakan klik salah satu PIN/Marker pada peta di atas untuk melihat detail.
                    </h4>
                </div>
            </div>
        </div>
    </main>

<?php include 'footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="dashboard.js"></script>

    <script>
        // Fungsi Dropdown Menu Profil Halaman Peta
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            if (dropdown.style.visibility === "visible") {
                dropdown.style.visibility = "hidden";
                dropdown.style.opacity = "0";
                dropdown.style.transform = "translateY(-15px)";
            } else {
                dropdown.style.visibility = "visible";
                dropdown.style.opacity = "1";
                dropdown.style.transform = "translateY(0)";
            }
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-wrapper')) {
                const dropdown = document.getElementById("profileDropdown");
                if (dropdown && dropdown.style.visibility === "visible") {
                    dropdown.style.visibility = "hidden";
                    dropdown.style.opacity = "0";
                    dropdown.style.transform = "translateY(-15px)";
                }
            }
        }

        // 1. Ambil data dari PHP
        const dataDariDatabase = <?php echo $json_lokasi; ?>;

        // 2. Inisialisasi Peta (Titik tengah di Purwokerto)
        const map = L.map('map').setView([-7.4245, 109.2302], 12);

       // 3. Tambahkan Tampilan Peta Google Maps (Hybrid / Satelit)
        L.tileLayer('http://mt0.google.com/vt/lyrs=y&hl=id&x={x}&y={y}&z={z}', {
            attribution: '© Google Maps',
            maxZoom: 20
        }).addTo(map);

        // 4. Custom Icon Merah
        const redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // 5. Looping data untuk membuat PIN/Marker di Peta
        for (const id in dataDariDatabase) {
            const data = dataDariDatabase[id];
            if (data.latitude && data.longitude) {
                const marker = L.marker([parseFloat(data.latitude), parseFloat(data.longitude)], {icon: redIcon}).addTo(map);
                marker.bindTooltip(`<b>${data.nama_tempat}</b>`);
                marker.on('click', function() {
                    ubahDetail(id);
                });
            }
        }

        // 6. Fungsi untuk Mengubah Panel Detail di bawah peta
        function ubahDetail(idLokasi) {
            if (dataDariDatabase[idLokasi]) {
                const data = dataDariDatabase[idLokasi];
                const tempatDetail = document.getElementById('tempat-detail');
                const arraySampah = data.kategori_sampah.split(',');
                
                let listSampahHTML = '<ul>';
                arraySampah.forEach(item => {
                    if(item.trim() !== "") {
                        listSampahHTML += `<li><i class="fas fa-check" style="color: #1e4d40; margin-right: 8px;"></i>${item.trim()}</li>`; 
                    }
                });
                listSampahHTML += '</ul>';

                tempatDetail.innerHTML = `
                    <h4 style="color: #1e4d40; font-size: 18px; margin-bottom: 5px;">${data.nama_tempat}</h4>
                    <p class="address" style="color: #555; margin-bottom: 15px;">${data.alamat}</p>
                    <h5 style="color: #1e4d40; font-size: 14px; margin-bottom: 5px;">SAMPAH YANG DITERIMA:</h5>
                    <div class="trash-list">
                        ${listSampahHTML}
                    </div>
                `;
            }
        }
    </script>
</body>
</html>