<?php
session_start();
require "../config/db.php";

// Ensure admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $location = trim($_POST['location']);
    $role = $_POST['role'];

    try {
        // Check if username/email exists for another user
        $check = $conn->prepare("SELECT * FROM users WHERE (username = :username OR email = :email) AND id != :id");
        $check->bindParam(':username', $username);
        $check->bindParam(':email', $email);
        $check->bindParam(':id', $id);
        $check->execute();

        if ($check->rowCount() > 0) {
            echo "<script>alert('Username or email already exists for another user.'); window.history.back();</script>";
            exit();
        }

        // Update user
        $stmt = $conn->prepare("UPDATE users SET name = :name, surname = :surname, username = :username, email = :email, location = :location, role = :role WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        echo "<script>alert('User updated successfully!'); window.location.href='../admin/dashboard.php';</script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
