<?php
session_start();
require "../config/db.php"; // PDO connection

// Ensure only admin can add users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and sanitize input
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $location = trim($_POST['location']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if username or email already exists
        $check = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $check->bindParam(':username', $username);
        $check->bindParam(':email', $email);
        $check->execute();

        if ($check->rowCount() > 0) {
            echo "<script>alert('Username or email already exists.'); window.history.back();</script>";
            exit();
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, surname, username, email, location, password, role) 
                                VALUES (:name, :surname, :username, :email, :location, :password, :role)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        $stmt->execute();

        echo "<script>alert('User added successfully!'); window.location.href='../admin/dashboard.php';</script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
        exit();
    }
} else {
    // Redirect if not POST
    header("Location: add_user.php");
    exit();
}
?>
