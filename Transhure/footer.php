<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="footer.css?v=1.2">

<div class="trashure-footer-extended">
    <div class="footer-vertical-container">
        
        <div class="footer-section">
            <h3>TENTANG KAMI</h3>
            <div class="about-text">
                <p>Di Trashure, kami believe bahwa bumi yang bersih dan lestari tidak dibangun oleh satu orang yang melakukan segalanya dengan sempurna, melainkan oleh jutaan orang yang mengambil langkah kecil bersama-sama. Berangkat dari keyakinan tersebut, Trashure hadir untuk mengubah cara kita memandang dan mengelola apa yang sering kita sebut sebagai "sampah".</p>
                <p>Trashure adalah platform keberlanjutan interaktif berbasis komunitas (crowdsourcing) yang menghubungkan kepedulian Anda dengan aksi nyata di lapangan. Kami bukan sekadar alat pencatat, kami adalah ruang kolaborasi digital di mana setiap individu, komunitas, dan penggerak lingkungan dapat bersatu untuk menciptakan peta masa depan yang lebih hijau.</p>
            </div>
        </div>

        <div class="footer-section">
            <h3>BUTUH BANTUAN? KONTAK KAMI!</h3>
            <div class="wa-container">
                <a href="https://wa.me/6289514920878?text=Halo%20Admin%20Trashure,%20saya%20butuh%20bantuan..." target="_blank" class="wa-button">
                    <i class="fab fa-whatsapp"></i> +62 895-1492-0878
                </a>
            </div>
        </div>

        <div class="footer-section">
            <h3>BERI RATING UNTUK KEMAJUAN KAMI</h3>
            <div class="rating-container">
                
                <form id="formRating">
                    <div class="stars">
                        <input type="radio" id="star5" name="rating" value="5" required />
                        <label for="star5" title="Sangat Menarik"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" />
                        <label for="star4" title="Menarik"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" />
                        <label for="star3" title="Cukup Menarik"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" />
                        <label for="star2" title="Kurang Menarik"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" />
                        <label for="star1" title="Membosankan"><i class="fas fa-star"></i></label>
                    </div>
                    
                    <div class="rating-labels">
                        <span>membosankan</span>
                        <span>kurang menarik</span>
                        <span>cukup menarik</span>
                        <span>menarik</span>
                        <span>sangat menarik</span>
                    </div>
                    
                    <textarea name="pesan" rows="4" placeholder="pesan dan kesan:" required></textarea>
                    
                    <button type="submit" class="btn-kirim-rating">Kirim Rating</button>
                </form>

            </div>
        </div>

    </div>
</div>

<footer>
    <div class="footer-logo">
        <img src="logo.png" alt="Trashure" onerror="this.style.display='none'">
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
<div class="footer-bottom">
    <p>Trashure © Copyright all right reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('formRating').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('proses_rating.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data === 'sukses') {
            Swal.fire({
                title: 'Terima Kasih!',
                text: 'Rating dan pesan Anda berhasil dikirim.',
                icon: 'success',
                confirmButtonColor: '#15A38F'
            });
            this.reset();
        } else {
            Swal.fire('Oops!', 'Terjadi kesalahan saat mengirim rating.', 'error');
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>