<?php
// Konfigurasi Email (PHPMailer)
// Harap isi dengan detail akun Gmail dan Sandi Aplikasi Anda.

$mail_config = [
    'host' => 'smtp.gmail.com',
    'port' => 465, // 465 untuk SSL, 587 untuk TLS
    'encryption' => 'ssl', // 'ssl' atau 'tls'
    
    // UBAH BAGIAN INI DENGAN EMAIL DAN SANDI APLIKASI ANDA:
    'username' => 'paudqu.alikhlas88@gmail.com', // Contoh: adminpaudqu@gmail.com
    'password' => 'bnehdtooosgnhwhz', // Contoh: xxxx xxxx xxxx xxxx (tanpa spasi)
    
    'from_email' => 'paudqu.alikhlas88@gmail.com', // Email yang akan muncul sebagai pengirim
    'from_name' => 'Admin PAUDQU Al-Ikhlas'
];
?>
