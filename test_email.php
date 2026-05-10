<?php
require_once 'admin/config_mail.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP();
    $mail->Host       = $mail_config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $mail_config['username'];
    $mail->Password   = $mail_config['password'];
    $mail->SMTPSecure = $mail_config['encryption'];
    $mail->Port       = $mail_config['port'];

    $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
    $mail->addAddress('test@example.com');

    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email';

    $mail->send();
    echo "Message has been sent";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
