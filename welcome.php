<?php session_start();
include_once('config.php');

if (strlen($_SESSION['ulogin']==0)) {
  header('location:index.php');
  } else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | Passwordless Auth</title>
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
            <div class="bg-indigo-600 px-6 py-4">
                <h2 class="text-3xl font-bold text-white text-center font-pacifico">
                    Welcome
                </h2>
            </div>
            <div class="px-6 py-8">
                <div class="text-center mb-8">
                    <p class="text-gray-700 text-lg">
                        Welcome Back —
                        <span class="font-semibold text-indigo-600">
                            <?php echo htmlspecialchars($_SESSION['fname']); ?>
                        </span>
                    </p>
                </div>
                <div class="mt-6">
                    <a href="logout.php" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>