<?php
session_start();
require "../config/db.php";

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Make sure an ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$userId = intval($_GET['id']);

// Prevent admin from deleting themselves (optional safety)
if ($userId === $_SESSION['user_id']) {
    echo "<script>alert('You cannot delete your own account.'); window.location.href='../admin/dashboard.php';</script>";
    exit();
}

// Delete the user
try {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    echo "<script>alert('User deleted successfully'); window.location.href='../admin/dashboard.php';</script>";

} catch (PDOException $e) {
    echo "<script>alert('Error deleting user: " . $e->getMessage() . "'); window.location.href='../admin/dashboard.php';</script>";
}
?>
