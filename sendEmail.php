<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

function sendEmail($to, $subject, $htmlContent, $fromEmail, $fromName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.server'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@stumav.com'; // Replace with your email
        $mail->Password = '****************'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // DKIM settings
        $mail->DKIM_domain = 'exapmle.com';
        $mail->DKIM_private = './private.key'; // Path to your private key
        $mail->DKIM_selector = 'default'; // Selector for your DKIM record
        $mail->DKIM_passphrase = ''; // Leave empty if no passphrase
        $mail->DKIM_identity = $mail->From;

        // Recipients
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->addReplyTo($fromEmail, $fromName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlContent;
        $mail->addCustomHeader('List-Unsubscribe', '<mailto:no-reply@stumav.com?subject=unsubscribe>');

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
