<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true;
    $mail->Username   = 'stockease004@gmail.com'; // Your Gmail address
    $mail->Password   = 'geasfnfohpiqdfjt'; // Use your new App Password (NO SPACES)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587; 

    // Email Headers
    $mail->setFrom('stockease004@gmail.com', 'Stockease'); 
    $mail->addAddress('smynampati04@gmail.com', 'Sreeja'); // Replace with recipient email

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHP';
    $mail->Body    = '<h1>Hello, This is a test email from PHP using Gmail SMTP!</h1>';
    $mail->AltBody = 'Hello, This is a test email from PHP using Gmail SMTP!';

    $mail->send();
    echo '✅ Message has been sent successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>