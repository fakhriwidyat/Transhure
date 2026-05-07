// Menangkap elemen tombol Google dan Facebook berdasarkan ID-nya
const googleBtn = document.getElementById('googleLoginBtn');
const facebookBtn = document.getElementById('facebookLoginBtn');

// Menambahkan aksi ketika tombol Google diklik
if(googleBtn) {
    googleBtn.addEventListener('click', function() {
        alert('Fitur login menggunakan akun Google sedang dalam tahap pengembangan. Silakan gunakan Username dan Password untuk saat ini.');
    });
}

// Menambahkan aksi ketika tombol Facebook diklik
if(facebookBtn) {
    facebookBtn.addEventListener('click', function() {
        alert('Fitur login menggunakan akun Facebook sedang dalam tahap pengembangan. Silakan gunakan Username dan Password untuk saat ini.');
    });
}