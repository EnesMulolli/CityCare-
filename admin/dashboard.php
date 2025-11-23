<?php
session_start();
require "../config/db.php";

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all users
$userStmt = $conn->prepare("SELECT id, name, surname, username, email, location, role, profile_image FROM users ORDER BY id DESC");
$userStmt->execute();
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent reports (last 5)
$reportStmt = $conn->prepare("SELECT r.id, r.title, r.status, r.created_at, r.location, r.image, u.username 
                              FROM reports r 
                              JOIN users u ON r.user_id = u.id 
                              ORDER BY r.created_at DESC LIMIT 5");
$reportStmt->execute();
$reports = $reportStmt->fetchAll(PDO::FETCH_ASSOC);

$profileImage = !empty($user['profile_image']) && file_exists("../uploads/" . $user['profile_image'])
    ? "../uploads/" . $user['profile_image']
    : "../uploads/default.webp";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Acme&family=Concert+One&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Share+Tech&family=Stack+Sans+Notch:wght@200..700&family=Teko:wght@300..700&display=swap" rel="stylesheet">

<title>Admin Dashboard – CityCare</title>

<style>
body {
    margin: 0;
    font-family: "Stack Sans Notch", sans-serif;
    background: #f2f5f7;
    color: #333;
}

header {
    width: 100%;
    background: #284b63;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 30px;
}

/* Logo */
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
    font-family: "Stack Sans Notch", sans-serif;
    font-weight: 700;
    margin: 0;
}

/* Nav links */
header .nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
}

header .nav-links li a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 8px 15px;
    border-radius: 6px;
    transition: 0.2s;
}

header .nav-links li a:hover {
    background: #3c6e71;
}

/* Responsive */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 10px;
    }

    .nav-links {
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }
}

.admin-actions {
    margin-bottom: 20px;
    text-align: right;
}

.btn-add-user {
    padding: 10px 18px;
    background: #284b63;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-add-user:hover {
    background: #3c6e71;
}

.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

/* Users table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 40px;
}

th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #3c6e71;
    color: white;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #e0f0f5;
}

/* Action buttons */
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
    color: white;
}

.update-btn:hover {
    background: #3d66d1;
}

.delete-btn {
    background: #ff4d4d;
    color: white;
}

.delete-btn:hover {
    background: #e03c3c;
}

/* Cards for stats */
.stats-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.card {
    flex: 1 1 200px;
    background: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card h3 {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #284b63;
}

.card p {
    font-size: 1rem;
    color: #555;
}

/* Recent reports */
.recent-reports h2 {
    margin-bottom: 20px;
    color: #284b63;
}

.reports-table th {
    background: #284b63;
    color: white;
}

.reports-table td, .reports-table th {
    padding: 12px 15px;
    border: 1px solid #ddd;
    vertical-align: middle;
}

.reports-table tr:nth-child(even) {
    background: #f9f9f9;
}

.reports-table tr:hover {
    background: #e0f0f5;
}

.reports-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}

/* Responsive */
@media(max-width: 768px) {
    .stats-cards {
        flex-direction: column;
    }

    table, .reports-table {
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
            <li><a href="../public/index.php">Home</a></li>
            <li><a href="all_users.php">Users</a></li>
            <li><a href="manage_reports.php">Reports</a></li>
            <li><a href="../dashboard/profile.php">Profile</a></li>
            <li><a href="../public/logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container">

    <!-- Stats cards -->
    <div class="stats-cards">
        <div class="card">
            <h3><?= count($users) ?></h3>
            <p>Total Users</p>
        </div>
        <div class="card">
            <h3><?= count($reports) ?></h3>
            <p>Total Reports</p>
        </div>
    </div>

    <!-- Users Table -->
    <h2>Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Username</th>
            <th>Email</th>
            <th>Profile</th>
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
            <td><img src="<?= $profileImage ?>" alt="Profile Image" width="50" height="50"></td>
            <td><?= htmlspecialchars($user['location']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a class="action-btn update-btn" href="update_user.php?id=<?= $user['id'] ?>">Update</a>
                <a class="action-btn delete-btn" href="../api/delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
            <div class="admin-actions">
                <a href="add_user.php" class="btn-add-user">+ Add User</a>
            </div>
    <!-- Recent Reports -->
    <div class="recent-reports">
        <h2>Recent Reports</h2>
        <table class="reports-table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Location</th>
                <th>Image</th>
                <th>Submitted By</th>
                <th>Date</th>
            </tr>
            <?php foreach($reports as $report): ?>
            <tr>
                <td><?= htmlspecialchars($report['id']) ?></td>
                <td><?= htmlspecialchars($report['title']) ?></td>
                <td><?= htmlspecialchars($report['status']) ?></td>
                <td><?= htmlspecialchars($report['location']) ?></td>
                <td>
                    <?php if($report['image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($report['image']) ?>" alt="Report Image">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($report['username']) ?></td>
                <td><?= htmlspecialchars($report['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</body>
</html>
