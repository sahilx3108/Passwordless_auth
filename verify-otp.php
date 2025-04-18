<?php session_start();
include_once('config.php');
error_reporting(0);

//validation page
if($_SESSION['emailid']=='' ){
echo "<script>window.location.href='login.php'</script>";
}else{

//Code for otp verification
if(isset($_POST['verify'])){
//Getting Post values
$emailid=$_SESSION['emailid'];	
$otp=$_POST['emailotp'];	
// Getting otp from database on the behalf of the email
$stmt=$dbh->prepare("SELECT emailOtp FROM tblusers where emailId=:emailid");
$stmt->execute(array(':emailid'=>$emailid)); 
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
$dbotp=$row['emailOtp'];
}
if($dbotp!=$otp){
echo "<script>alert('Please enter correct OTP');</script>";	
} else {
$emailverifiy=1;
$sql="update tblusers set isEmailVerify=:emailverifiy where emailId=:emailid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':emailid',$emailid,PDO::PARAM_STR);
$query->bindParam(':emailverifiy',$emailverifiy,PDO::PARAM_STR);
$query->execute();	
session_destroy();
echo "<script>alert('OTP verified successfully');</script>";	
echo "<script>window.location.href='login.php'</script>";
}}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify OTP | Passwordless Auth</title>
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
                        Verify OTP
                    </h2>
                    <p class="text-indigo-100 mt-2 font-courgette">
                        Enter the OTP sent to your email
                    </p>
                </div>
            </div>
            <div class="px-6 py-8">
                <form method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label for="emailotp" class="block text-sm font-medium text-gray-700">Email OTP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="emailotp" id="emailotp" maxlength="6" required
                                class="input-focus pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter 6-digit OTP">
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="verify"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Verify OTP
                        </button>
                    </div>
                </form>
                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-600">Didn't receive the OTP?</span>
                    <a href="resend-otp.php" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                        Resend OTP
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>