<?php
session_start();
require "../config/db.php"; // your PDO connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['user']);   // username or email
    $password = $_POST['password'];

    // Check if login matches username OR email
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :login OR email = :login LIMIT 1");
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Password verification & session setup
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // important!

        // Redirect based on role
        if ($user['role'] === "admin") {
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            header("Location: ../user/dashboard.php");
            exit();
        }
    } else {
        echo "<script>alert('Incorrect login or password'); window.location.href='login.php';</script>";
    }
}
?>
