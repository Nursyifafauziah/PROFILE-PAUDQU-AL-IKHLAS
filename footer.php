<?php
$sql_footer_profil = "SELECT * FROM profil WHERE id = 1";
$res_footer_profil = $conn->query($sql_footer_profil);
$profil_footer = $res_footer_profil ? $res_footer_profil->fetch_assoc() : [];
$alamat_footer = !empty($profil_footer['alamat_kontak']) ? htmlspecialchars($profil_footer['alamat_kontak']) : 'Jl. Pendidikan No. 123, Kota Bahagia';
$email_footer = !empty($profil_footer['email']) ? htmlspecialchars($profil_footer['email']) : 'info@paudqualikhlas.sch.id';
$telepon_footer = !empty($profil_footer['telepon']) ? htmlspecialchars($profil_footer['telepon']) : '+62 812 3456 7890';
?>
<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-4 mt-5" style="position: relative;">
    <div class="container text-center text-md-start pt-4">
        <div class="row text-center text-md-start">
            <div class="col-12 col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-success">PAUDQU Al-Ikhlas</h5>
                <p>Membentuk generasi qurani yang berakhlak mulia, cerdas, dan mandiri sejak usia dini. Dengan Ilmu, Hidup Jadi Bermutu.</p>
            </div>
            
            <div class="col-12 col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-success">Tautan Berguna</h5>
                <p><a href="index.php" class="text-white text-decoration-none">Beranda</a></p>
                <p><a href="about.php" class="text-white text-decoration-none">Tentang Kami</a></p>
                <p><a href="guru.php" class="text-white text-decoration-none">Data Guru</a></p>
                <p><a href="kontak.php" class="text-white text-decoration-none">Hubungi Kami</a></p>
            </div>
            
            <div class="col-12 col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-success">Kontak</h5>
                <p><i class="fas fa-home mr-3"></i> <?= $alamat_footer ?></p>
                <p><i class="fas fa-envelope mr-3"></i> <?= $email_footer ?></p>
                <p><i class="fas fa-phone mr-3"></i> <?= $telepon_footer ?></p>
            </div>
        </div>
        <hr class="mb-4">
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-12 text-center">
                <p>Copyright &copy; <?= date('Y'); ?> All rights reserved by: <strong>PAUDQU Al-Ikhlas</strong> | <a href="admin/login.php" class="text-white text-decoration-none opacity-50 small">Admin Area</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
// Dekorasi Islami Anak-anak yang Rapi (Neat)
document.addEventListener("DOMContentLoaded", function() {
    const emojis = ['🕌', '🧕', '👦', '👧', '👳', '📖', '✨', '⭐', '🌙', '🕋'];
    
    function createNeatEmojis(container, type) {
        if (getComputedStyle(container).position === 'static') {
            container.style.position = 'relative';
        }
        
        // Titik koordinat simetris dan rapi di pinggir, jumlah dikurangi agar tidak terlalu ramai
        let positions = [];
        if (type === 'hero') {
            positions = [
                // Kiri
                {l: 8, t: 15, s: 2.5}, {l: 12, t: 45, s: 1.8}, 
                // Kanan
                {l: 85, t: 12, s: 2}, {l: 90, t: 40, s: 2.2},
                // Tengah atas
                {l: 60, t: 8, s: 1.5}
            ];
        } else {
            // Untuk page header yang lebih pendek (hanya 4 emoji)
            positions = [
                // Kiri
                {l: 10, t: 15, s: 2}, {l: 25, t: 25, s: 1.2},
                // Kanan
                {l: 85, t: 12, s: 1.8}, {l: 75, t: 28, s: 1.5}
            ];
        }
        
        // Acak urutan emoji agar bervariasi
        let shuffledEmojis = emojis.sort(() => 0.5 - Math.random());
        
        positions.forEach((pos, index) => {
            let el = document.createElement('div');
            let span = document.createElement('span');
            
            span.innerText = shuffledEmojis[index % shuffledEmojis.length];
            let rotate = Math.random() * 30 - 15; // -15 to 15 deg
            span.style.cssText = `display: inline-block; transform: rotate(${rotate}deg);`;
            el.appendChild(span);
            
            let animDuration = Math.random() * 3 + 3; 
            let delay = Math.random() * -5; 
            
            el.style.cssText = `
                position: absolute;
                left: ${pos.l}%;
                top: ${pos.t}%;
                font-size: ${pos.s}rem;
                opacity: 0.35;
                pointer-events: none;
                z-index: 0;
                animation: float ${animDuration}s ease-in-out ${delay}s infinite alternate;
            `;
            container.appendChild(el);
        });
    }

    let hero = document.querySelector('.hero-section');
    if (hero) createNeatEmojis(hero, 'hero'); 

    let headers = document.querySelectorAll('.page-header');
    headers.forEach(h => createNeatEmojis(h, 'header')); 
});
</script>
</body>
</html>
