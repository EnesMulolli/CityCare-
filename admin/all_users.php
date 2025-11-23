<?php
session_start();
require "../config/db.php";

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Fetch users
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM users 
                            WHERE name LIKE :search 
                            OR surname LIKE :search 
                            OR username LIKE :search 
                            OR email LIKE :search 
                            OR location LIKE :search
                            ORDER BY id DESC");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Users Dashboard – CityCare</title>

<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f4f6f8;
    margin: 0;
    color: #333;
}

/* Header / Navbar (original) */
header {
    width: 100%;
    background: #284b63;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 30px;
}

.header-container .logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-container .logo img {
    width: 50px;
}

.header-container .logo h2 {
    color: #fff;
    font-weight: 700;
    font-size: 1.5rem;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-links li a {
    color: #fff;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 6px;
    transition: 0.3s;
}

.nav-links li a:hover {
    background: #3c6e71;
}

/* Main container */
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

/* Search form (updated) */
.search-form {
    margin-bottom: 20px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.search-form input[type="text"] {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    flex: 1;
    min-width: 180px;
    font-size: 14px;
}

.search-form button {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    background: #284b63;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-size: 14px;
}

.search-form button:hover {
    background: #3c6e71;
}

/* Users table */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
    vertical-align: middle;
}

th {
    background: #284b63;
    color: #fff;
    font-weight: 600;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #e0f0f5;
}

.reports-table img,
td img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

/* Buttons */
.action-btn {
    padding: 6px 12px;
    margin: 0 2px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
}

.update-btn {
    background: #4e7cff;
    color: #fff;
}

.update-btn:hover {
    background: #3d66d1;
}

.delete-btn {
    background: #ff4d4d;
    color: #fff;
}

.delete-btn:hover {
    background: #e03c3c;
}

/* Responsive */
@media(max-width: 768px) {
    .nav-links {
        flex-direction: column;
        gap: 10px;
    }

    table {
        font-size: 14px;
    }
}


</style>
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <img src="../assets/img/giphy.gif" alt="CityCare Logo">
            <h2>CityCare</h2>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="all_users.php">All Users</a></li>
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="../api/logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container">

    <h2>All Users</h2>

    <!-- Search -->
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by name, username, email, or location" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Username</th>
            <th>Email</th>
            <th>Profile Image</th>
            <th>Location</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['surname']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <?php 
                $img = !empty($user['profile_image']) && file_exists("../uploads/".$user['profile_image']) 
                    ? "../uploads/".$user['profile_image'] 
                    : "../uploads/default.webp"; 
                ?>
                <img src="<?= $img ?>" alt="Profile Image">
            </td>
            <td><?= htmlspecialchars($user['location']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a class="action-btn update-btn" href="update_user.php?id=<?= $user['id'] ?>">Update</a>
                <a class="action-btn delete-btn" href="../api/delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
