<?php
session_start();
require "../config/db.php"; // PDO connection

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User not found'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update User – Admin</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f2f5f7;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 60px auto;
    background: #fff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

h2 {
    text-align: center;
    color: #284b63;
    margin-bottom: 25px;
    font-size: 2rem;
}

form input, form select {
    width: 100%;
    padding: 12px 14px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #d1d1d1;
    font-size: 15px;
    transition: 0.3s;
}

form input:focus, form select:focus {
    border-color: #284b63;
    outline: none;
    box-shadow: 0 0 6px rgba(40,75,99,0.3);
}

button {
    width: 100%;
    padding: 14px;
    margin-top: 15px;
    border: none;
    border-radius: 8px;
    background: #284b63;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #3c6e71;
}

.back-link {
    margin-top: 15px;
    text-align: center;
}

.back-link a {
    color: #284b63;
    text-decoration: none;
    font-weight: bold;
}

.back-link a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="container">
    <h2>Update User</h2>
    <form action="../api/update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="text" name="surname" placeholder="Surname" value="<?= htmlspecialchars($user['surname']) ?>" required>
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="text" name="location" placeholder="Address" value="<?= htmlspecialchars($user['location']) ?>" required>
        <select name="role" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">Update User</button>
    </form>

    <div class="back-link">
        <a href="dashboard.php">&larr; Back to Dashboard</a>
    </div>
</div>

</body>
</html>
