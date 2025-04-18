<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            echo "Signup successful. <a href='login.html'>Login here</a>";
        } else {
            echo "Error: Could not create account.";
        }
        $stmt->close();
    } else {
        echo "Error preparing query.";
    }
    $conn->close();
}
?>
