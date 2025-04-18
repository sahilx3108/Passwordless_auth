<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sahilx3108@gmail.com';
        $mail->Password   = 'ratg jily enbe nult';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('sahilx3108@gmail.com', 'Sahil');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: <b>$otp</b><br>This code will expire in 5 minutes.";
        $mail->AltBody = "Your OTP code is: $otp\nThis code will expire in 5 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = rand(100000, 999999); // Generate 6-digit OTP
    
    if (sendOTP($email, $otp)) {
        // Store OTP in session or database
        session_start();
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();
        $_SESSION['email'] = $email;
        
        echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
    }
}
?> 