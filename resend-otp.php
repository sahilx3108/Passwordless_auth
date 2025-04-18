<?php session_start();
include_once('config.php');
require 'vendor/autoload.php';

//Code for Resend
if(isset($_POST['resend'])){
//Getting Post values
$email=$_POST['email'];	
//Generating 6 Digit Random OTP
$otp= mt_rand(100000, 999999);	
// Query for validation of  email-id
$ret="SELECT id,isEmailVerify FROM  tblusers where (emailId=:uemail)";
$queryt = $dbh -> prepare($ret);
$queryt->bindParam(':uemail',$email,PDO::PARAM_STR);
$queryt -> execute();
$results = $queryt -> fetchAll(PDO::FETCH_OBJ);
if($queryt -> rowCount() > 0)
{
foreach ($results as $result) {
$verifystatus=$result->isEmailVerify;}	

//if email already verified
if($verifystatus=='1'){
echo "<script>alert('Email already verified. No need to verify again.');</script>";
} else{
$_SESSION['emailid']=$email;
$_SESSION['otp']=$otp;

$sql="update tblusers set emailOtp=:otp where emailId=:emailid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':emailid',$email,PDO::PARAM_STR);
$query->bindParam(':otp',$otp,PDO::PARAM_STR);
$query->execute();	
//Code for Sending Email using PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sahilx3108@gmail.com';
    $mail->Password   = 'ratg jily enbe nult';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('sahilx3108@gmail.com', 'Sahil');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'OTP Verification';
    $mail->Body    = "<html><body><div><div>Dear User,</div><br><br>";
    $mail->Body   .= "<div style='padding-top:8px;'>Your new OTP for Account Verification is <b>$otp</b></div><div></div></body></html>";
    $mail->AltBody = "Dear User,\n\nYour new OTP for Account Verification is $otp";

    $mail->send();
    echo "<script>window.location.href='verify-otp.php'</script>";
} catch (Exception $e) {
    echo "<script>alert('Failed to send OTP. Please try again.');</script>";
}
}}else {
echo "<script>alert('Email id not registered yet');</script>";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resend OTP | Passwordless Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Courgette&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <h2 class="text-3xl font-bold text-white text-center font-pacifico">
                    Resend OTP
                </h2>
            </div>
            <div class="px-6 py-8">
                <form method="post" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter your email">
                    </div>
                    <div>
                        <button type="submit" name="resend"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Resend OTP
                        </button>
                    </div>
                </form>
                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-600">Already have an account?</span>
                    <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Login here
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>