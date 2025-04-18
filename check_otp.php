
<?php
session_start();
$input_otp = $_POST['otp'];

if ($_SESSION['otp'] === $input_otp) {
    $_SESSION['user'] = $_SESSION['email'];
    echo "Login successful! <a href='index.html'>Go to Home</a>";
} else {
    echo "Invalid OTP. <a href='verify_otp.php'>Try again</a>";
}
?>
