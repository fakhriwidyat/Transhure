// =========================================
// 1. FUNGSI DROPDOWN PROFIL
// =========================================

// Fungsi untuk buka/tutup menu
function toggleDropdown() {
    document.getElementById("profileDropdown").classList.toggle("show");
}

// Fungsi otomatis nutup menu kalau user ngeklik di tempat lain
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

// =========================================
// 2. FUNGSI POPUP LOGOUT (SWEETALERT)
// =========================================

function konfirmasiLogout(event) {
    event.preventDefault(); // Mencegah klik langsung pindah halaman

    Swal.fire({
        title: 'Keluar dari Trashure?',
        text: "Sesi kamu akan diakhiri.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c', 
        cancelButtonColor: '#15A38F', 
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        background: '#ffffff',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user klik "Ya", arahkan ke file logout.php
            window.location.href = 'logout.php';
        }
    });
}

function tampilkanPeta() {
    // Menyembunyikan teks "Disini Gambar petanya ya"
    document.getElementById('teksPeta').style.display = 'none';
    
    // Menampilkan iframe Google Maps
    document.getElementById('googleMaps').style.display = 'block';
    
    // Mematikan efek klik agar peta bisa digeser-geser oleh user
    var wadah = document.getElementById('wadahPeta');
    wadah.onclick = null;
    wadah.style.cursor = 'default';
}