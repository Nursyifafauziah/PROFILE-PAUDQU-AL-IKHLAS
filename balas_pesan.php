<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pesan']) && isset($_POST['balasan_teks'])) {
    $id = intval($_POST['id_pesan']);
    $balasan = $conn->real_escape_string($_POST['balasan_teks']);

    // Get the sender's email and name
    $sql_get = "SELECT nama, email, isi_pesan FROM pesan WHERE id = $id";
    $result = $conn->query($sql_get);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email_pengirim = $row['email'];
        $nama_pengirim = $row['nama'];
        $isi_pesan_asli = $row['isi_pesan'];

        // Update database
        $sql_update = "UPDATE pesan SET balasan = '$balasan', status = 'dibalas' WHERE id = $id";
        
        if ($conn->query($sql_update)) {
            
            // Try to send email using PHPMailer
            require_once 'config_mail.php';
            require_once '../PHPMailer/src/Exception.php';
            require_once '../PHPMailer/src/PHPMailer.php';
            require_once '../PHPMailer/src/SMTP.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = $mail_config['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $mail_config['username'];
                $mail->Password   = $mail_config['password'];
                $mail->SMTPSecure = $mail_config['encryption'];
                $mail->Port       = $mail_config['port'];

                // Recipients
                $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
                $mail->addAddress($email_pengirim, $nama_pengirim);

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Balasan dari PAUDQU Al-Ikhlas";
                
                // HTML Email Body
                $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;'>
                    <div style='background-color: #28a745; color: white; padding: 20px; text-align: center;'>
                        <h2 style='margin: 0;'>Pesan Balasan PAUDQU Al-Ikhlas</h2>
                    </div>
                    <div style='padding: 20px; color: #333;'>
                        <p>Halo <strong>" . htmlspecialchars($nama_pengirim) . "</strong>,</p>
                        <p>Terima kasih telah menghubungi kami. Berikut adalah balasan untuk pesan Anda:</p>
                        
                        <div style='background-color: #f8f9fa; border-left: 4px solid #ccc; padding: 10px 15px; margin: 20px 0;'>
                            <small style='color: #666;'>Pesan Anda:</small><br>
                            <i>\"" . nl2br(htmlspecialchars($isi_pesan_asli)) . "\"</i>
                        </div>
                        
                        <div style='background-color: #e8f5e9; border-left: 4px solid #28a745; padding: 10px 15px; margin: 20px 0;'>
                            <small style='color: #28a745; font-weight: bold;'>Balasan Kami:</small><br>
                            " . nl2br(htmlspecialchars($_POST['balasan_teks'])) . "
                        </div>
                        
                        <p style='margin-top: 30px;'>Salam Hangat,<br><strong>Admin PAUDQU Al-Ikhlas</strong></p>
                    </div>
                    <div style='background-color: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #777;'>
                        Ini adalah email otomatis, mohon untuk tidak membalas langsung ke alamat email ini.
                    </div>
                </div>";
                
                $mail->Body    = $body;
                $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

                if ($mail_config['username'] !== 'emailanda@gmail.com') {
                    $mail->send();
                    $_SESSION['pesan_aksi'] = "Pesan berhasil dibalas dan email berhasil dikirim ke " . htmlspecialchars($email_pengirim);
                } else {
                    $_SESSION['pesan_aksi'] = "Pesan berhasil dibalas dan disimpan. (Email tidak dikirim karena konfigurasi di config_mail.php belum disetel)";
                }
            } catch (Exception $e) {
                $_SESSION['pesan_error'] = "Balasan tersimpan di database, tetapi email gagal dikirim. Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['pesan_error'] = "Gagal menyimpan balasan: " . $conn->error;
        }
    } else {
        $_SESSION['pesan_error'] = "Pesan tidak ditemukan.";
    }
}

header("Location: pesan.php");
exit();
?>
