<?php
session_start();
require "../config/db.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];


// Fetch current user info
$stmtUser = $conn->prepare("SELECT id, name, surname, username, email, location, profile_image FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Set profile image
$profileImage = !empty($user['profile_image']) && file_exists("../uploads/" . $user['profile_image'])
    ? "../uploads/" . $user['profile_image']
    : "../uploads/default.webp";

// Fetch all reports for this user
$stmtReports = $conn->prepare("SELECT r.*, u.name, u.surname FROM reports r JOIN users u ON r.user_id = u.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
$stmtReports->execute([$userId]);
$reports = $stmtReports->fetchAll(PDO::FETCH_ASSOC);

// Count total reports
$totalReports = count($reports);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard – CityCare</title>

<style>
/* Reset & Base Styles */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #f4f6f8; color: #333; line-height: 1.6; }
a { text-decoration: none; color: inherit; }

/* Header Section */
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
.header-container .logo img { width: 50px; }
.header-container .logo h2 { color: #fff; font-weight: 700; font-size: 1.5rem; }
.nav-links { list-style: none; display: flex; gap: 20px; }
.nav-links li a {
    color: #fff;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 6px;
    transition: 0.3s;
}
.nav-links li a:hover { background: #3c6e71; }

/* Main Container */
.container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

/* Statistics Cards */
.stats-cards { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
.card {
    flex: 1 1 200px;
    background: #fff;
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.card h3 { font-size: 2rem; margin-bottom: 8px; color: #284b63; }
.card p { font-size: 1rem; color: #555; }

/* Table Styles */
table {
    width: 110%; /* wider table */
    max-width: none;
    border-collapse: collapse;
    margin-bottom: 40px;
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
tr:nth-child(even) { background: #f9f9f9; }
tr:hover { background: #e0f0f5; }

.reports-table img {
    width: 70px;      /* slightly bigger */
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    transition: transform 0.2s;
}
.reports-table img:hover {
    transform: scale(1.1);
    cursor: pointer;
}

/* Status Display */
.status-display {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    background: #e0e0e0;
    color: #333;
    font-weight: 600;
    font-size: 14px;
    min-width: 100px;
    text-align: center;
}

/* Add report button */
.add-report-btn {
    display: inline-block;
    padding: 12px 20px;
    background: #284b63;
    color: #fff;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    margin-top: 15px;
    transition: 0.3s;
}
.add-report-btn:hover { background: #3c6e71; }

/* Responsive Design */
@media(max-width: 768px) {
    .stats-cards { flex-direction: column; }
    .nav-links { flex-direction: column; gap: 10px; }
    table { font-size: 14px; }
}

/* Modal styles for image popup */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    justify-content: center;
    align-items: center;
}
.modal img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 8px;
}
.modal:target {
    display: flex;
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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
            <li><a href="my_reports.php">My Reports</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="../public/logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container">

    <!-- Stats cards -->
    <div class="stats-cards">
        <div class="card">
            <h3><?= $totalReports ?></h3>
            <p>Total Reports Submitted</p>
        </div>
    </div>

    <!-- All Reports -->
    <h2>My Reports</h2>
    <table class="reports-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Location</th>
            <th>Image</th>
            <th>User Name</th>
            <th>Date</th>
        </tr>
        <?php foreach($reports as $report): ?>
        <tr>
            <td><?= htmlspecialchars($report['id']) ?></td>
            <td><?= htmlspecialchars($report['title']) ?></td>
            <td><?= htmlspecialchars($report['description']) ?></td>
            <td>
                <span class="status-display"><?= htmlspecialchars($report['status']) ?></span>
            </td>
            <td><?= htmlspecialchars($report['location']) ?></td>
            <td>
    <?php if($report['image'] && file_exists("../uploads/" . $report['image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($report['image']) ?>" alt="Report Image" class="clickable-image">
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>

            <td><?= htmlspecialchars($report['name'] . ' ' . $report['surname']) ?></td>
            <td><?= htmlspecialchars($report['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="../public/report_issues.php" class="add-report-btn">
        + Report Something
    </a>
</div>
<script src="../assets/js/report_issues.js"></script>
</body>
</html>
