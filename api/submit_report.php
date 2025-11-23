<?php
session_start();
require_once "../config/db.php"; // PDO connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate required fields
if (!isset($_POST['title']) || empty($_POST['title']) ||
    !isset($_POST['description']) || !isset($_POST['location'])) {
    echo "Missing fields.";
    exit;
}

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$location = trim($_POST['location']);
$image_path = NULL;

// Handle Image Upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed = ["jpg", "jpeg", "png", "webp"];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "Invalid image format!";
        exit;
    }

    $filename = uniqid() . "." . $ext;
    $uploadPath = "../uploads/" . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        $image_path = $filename;
    }
}

// Insert report using PDO
$sql = "INSERT INTO reports (user_id, title, description, location, image, status)
        VALUES (:user_id, :title, :description, :location, :image, 'Pending')";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':title', $title, PDO::PARAM_STR);
$stmt->bindParam(':description', $description, PDO::PARAM_STR);
$stmt->bindParam(':location', $location, PDO::PARAM_STR);
$stmt->bindParam(':image', $image_path, PDO::PARAM_STR);

if ($stmt->execute()) {
    header("Location: ../user/my_reports.php?success=1");
    exit;
} else {
    echo "Error submitting report.";
}
?>
