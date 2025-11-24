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

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['status'])) {
    $updateStmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $updateStmt->execute([$_POST['status'], $_POST['report_id']]);
    header("Location: dashboard.php");
    exit();
}

// Handle report deletion
if (isset($_GET['delete_id'])) {
    $deleteStmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $deleteStmt->execute([$_GET['delete_id']]);
    header("Location: dashboard.php");
    exit();
}

// Fetch all reports
$stmtReports = $conn->prepare("SELECT r.*, u.name, u.surname FROM reports r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
$stmtReports->execute();
$reports = $stmtReports->fetchAll(PDO::FETCH_ASSOC);

// Count total reports
$totalReports = count($reports);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard -- CityCare</title>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Notch:wght@200..700&display=swap" rel="stylesheet">

<style>
/* Reset & Base */
* { box-sizing: border-box; margin:0; padding:0; }
body { font-family: sans-serif; background: #f4f6f8; color: #333; line-height: 1.6; }
a { text-decoration: none; color: inherit; }

/* Header */
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

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo img { width: 50px; }
.logo h2 { font-family: 'Stack Sans Notch', sans-serif; color: #fff; font-weight: 600; font-size: 2rem; }

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav-links li a {
    font-family: 'Stack Sans Notch', sans-serif;
    color: #fff;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 6px;
    transition: 0.3s;
}

.nav-links li a:hover { background: #3c6e71; }

/* Container */
.container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

/* Stats Cards */
.stats-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.card {
    flex: 1 1 200px;
    background: #fff;
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}

.card:hover { transform: translateY(-4px); box-shadow:0 8px 20px rgba(0,0,0,0.15); }

.card h3 { font-size: 2rem; margin-bottom: 8px; color: #284b63; }
.card p { font-size: 1rem; color: #555; }

/* Table */
table {
    width: 100%;
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

th { background: #284b63; color: #fff; font-weight: 600; }

tr:nth-child(even) { background: #f9f9f9; }
tr:hover { background: #e0f0f5; }

.reports-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}

/* Status Dropdown */
.status-select {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    cursor: pointer;
    font-weight: 500;
}

/* Search Input */
#searchInput {
    margin-bottom: 20px;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    width: 100%;
    max-width: 400px;
}

/* Responsive */
@media (max-width:768px) {
    .stats-cards { flex-direction: column; }
    .nav-links { flex-direction: column; width: 100%; gap: 10px; }
    table { font-size: 14px; }
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

    <!-- Search -->
    <input type="text" id="searchInput" placeholder="Search reports...">

    <!-- Reports Table -->
    <table class="reports-table" id="reportsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Location</th>
            <th>Image</th>
            <th>User Name</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($reports as $report): ?>
        <tr>
            <td><?= htmlspecialchars($report['id']) ?></td>
            <td><?= htmlspecialchars($report['title']) ?></td>
            <td><?= htmlspecialchars($report['description']) ?></td>
            <td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                    <select class="status-select" name="status" onchange="this.form.submit()">
                        <option value="Pending" <?= $report['status']=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="In Progress" <?= $report['status']=='In Progress'?'selected':'' ?>>In Progress</option>
                        <option value="Resolved" <?= $report['status']=='Resolved'?'selected':'' ?>>Resolved</option>
                        <option value="In Review" <?= $report['status']=='In Review'?'selected':'' ?>>In Review</option>
                    </select>
                </form>
            </td>
            <td><?= htmlspecialchars($report['location']) ?></td>
            <td>
                <?php if($report['image'] && file_exists("../uploads/" . $report['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($report['image']) ?>" alt="Report Image">
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($report['name'] . ' ' . $report['surname']) ?></td>
            <td><?= htmlspecialchars($report['created_at']) ?></td>
            <td>
                <a href="?delete_id=<?= $report['id'] ?>" style="color:red;" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Simple search filter
const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#reportsTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

</body>
</html>
