<?php session_start();
include_once('config.php');
require 'vendor/autoload.php';

//Code for Signup
if(isset($_POST['submit'])){
//Getting Post values
$name=$_POST['username'];	
$email=$_POST['email'];	
$cnumber=$_POST['contactnumber'];	
$loginpass=md5($_POST['password']); // Password is encrypted using MD5
//Generating 6 Digit Random OTP
$otp= mt_rand(100000, 999999);	
// Query for validation of  email-id
$ret="SELECT id FROM  tblusers where (emailId=:uemail)";
$queryt = $dbh -> prepare($ret);
$queryt->bindParam(':uemail',$email,PDO::PARAM_STR);
$queryt -> execute();
$results = $queryt -> fetchAll(PDO::FETCH_OBJ);
if($queryt -> rowCount() == 0)
{
//Query for Insert  user data if email not registered 
$emailverifiy=0;
$sql="INSERT INTO tblusers(userName,emailId,ContactNumber,userPassword,emailOtp,isEmailVerify) VALUES(:fname,:emaill,:cnumber,:hashedpass,:otp,:isactive)";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':fname',$name,PDO::PARAM_STR);
$query->bindParam(':emaill',$email,PDO::PARAM_STR);
$query->bindParam(':cnumber',$cnumber,PDO::PARAM_STR);
$query->bindParam(':hashedpass',$loginpass,PDO::PARAM_STR);
$query->bindParam(':otp',$otp,PDO::PARAM_STR);
$query->bindParam(':isactive',$emailverifiy,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$_SESSION['emailid']=$email;	
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
    $mail->Body    = "<html><body><div><div>Dear $name,</div><br><br>";
    $mail->Body   .= "<div style='padding-top:8px;'>Thank you for registering with us. OTP for Account Verification is <b>$otp</b></div><div></div></body></html>";
    $mail->AltBody = "Dear $name,\n\nThank you for registering with us. OTP for Account Verification is $otp";

    $mail->send();
    echo "<script>window.location.href='verify-otp.php'</script>";
} catch (Exception $e) {
    echo "<script>alert('Failed to send OTP. Please try again.');</script>";
}
}else {
echo "<script>alert('Something went wrong.Please try again');</script>";	
}} else{
echo "<script>alert('Email id already assicated with another account.');</script>";
}
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | Passwordless Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Courgette&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .bg-pattern {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            background-image: 
                radial-gradient(circle at 25px 25px, rgba(99, 102, 241, 0.15) 3%, transparent 0),
                radial-gradient(circle at 75px 75px, rgba(99, 102, 241, 0.15) 3%, transparent 0),
                radial-gradient(circle at 50px 50px, rgba(99, 102, 241, 0.1) 2%, transparent 0);
            background-size: 100px 100px;
            position: relative;
            overflow: hidden;
        }
        .bg-pattern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                linear-gradient(-45deg, rgba(99, 102, 241, 0.1) 0%, transparent 50%);
            z-index: -1;
        }
        .bg-pattern::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
            z-index: -1;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center bg-pattern">
    <div class="max-w-md w-full space-y-8 p-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden card-hover">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-8">
                <div class="text-center">
                    <h2 class="text-4xl font-bold text-white font-pacifico">
                        Create Account
                    </h2>
                    <p class="text-indigo-100 mt-2 font-courgette">
                        Join us and start your journey
                    </p>
                </div>
            </div>
            <div class="px-6 py-8">
                <form method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" required
                                class="input-focus pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your full name">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required
                                class="input-focus pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="contactnumber" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <input type="text" name="contactnumber" id="contactnumber" required
                                class="input-focus pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your contact number">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="input-focus pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your password">
                        </div>
                    </div>
                    <div>
                        <a href="resend-otp.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                            Resend OTP
                        </a>
                    </div>
                    <div>
                        <button type="submit" name="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Sign Up
                        </button>
                    </div>
                </form>
                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-600">Already have an account?</span>
                    <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                        Login here
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 