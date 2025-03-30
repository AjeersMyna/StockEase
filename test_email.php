<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'benedmathews@gmail.com'; // Replace with your email
    $mail->Password   = 'Neneraju@2030'; // Replace with your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Email Details
    $mail->setFrom('benedmathews@gmail.com', 'SreejaM');
    $mail->addAddress('smynampati04@gmail.com', 'Bened'); // Change recipient

    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = 'This is a test email sent using PHPMailer in PHP.';

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Email failed to send. Error: {$mail->ErrorInfo}";
}
?>
