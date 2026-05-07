document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Cek tipe input saat ini, lalu ubah ke teks atau password
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Ubah gambar ikon mata (terbuka/tertutup)
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});