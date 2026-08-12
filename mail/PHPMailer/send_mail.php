<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Use Composer's autoloader to automatically load PHPMailer from the vendor folder
require_once __DIR__ . '/../../vendor/autoload.php';

function sendMail($to, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // ⚠️ IMPORTANT: Replace these with your actual Gmail details
        $mail->Username   = 'yourgmail@gmail.com'; // Your full Gmail address
        $mail->Password   = 'YOUR_16_DIGIT_APP_PASSWORD'; // Your 16-character Google App Password
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('yourgmail@gmail.com', 'Complaint Management System');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Log the error message for debugging purposes
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>