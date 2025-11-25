<?php
// make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "../config/db.php"; // your DB connection (keep this or move above if already required)

// Fetch recent reports (optional - keep if you need $recentReports)
try {
    $recentStmt = $conn->prepare("
        SELECT r.*, u.name, u.surname 
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $recentStmt->execute();
    $recentReports = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // handle error or leave $recentReports empty
    $recentReports = [];
}


// Convenience flags
$logged = isset($_SESSION['user_id']);
$role   = $_SESSION['role'] ?? null;
$username = $_SESSION['username'] ?? null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CityCare</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Concert+One&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Share+Tech&family=Stack+Sans+Notch:wght@200..700&family=Teko:wght@300..700&display=swap" rel="stylesheet">

</head>
<body>

<nav>
    <div class="logo">
        <img src="../assets/img/ds-icon.png" alt="site icon">
        <h2>CityCare</h2>
    </div>

    <ul>
        <?php if ($logged): ?>
            <li>
                <?php if ($role === 'admin'): ?>
                    <a href="../admin/dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a href="../user/dashboard.php">My Activity</a>
                <?php endif; ?>
            </li>

            <li><a href="all_reports.php">View All Reports</a></li>
            <li><a href="common_issues.php">Common Issues</a></li>

        <?php else: ?>
            <li><a href="all_reports.php">View All Reports</a></li>
            <li><a href="common_issues.php">Common Issues</a></li>
        <?php endif; ?>
    </ul>

    <div class="nav-buttons">

        <?php if (!$logged): ?>
            <a href="login.php" class="login-btn">Sign In</a>

        <?php else: ?>
            <?php if ($username): ?>
                    
            <?php endif; ?>
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        <?php endif; ?>

        <a href="report_issues.php" class="report-btn">Report Now</a>
    </div>
</nav>


<section id="welcome">
    <h1>Make Your City Better</h1>
        <p>Report issues in your neighborhood. Track progress. See real change happen.</p>
            <div class="center-links">
                <a href="../public/report_issues.php">Report an Issue</a>
                <a href="">Track My Reports</a>
            </div>
    <input type="text" name="search-location" id="search-location" placeholder="Enter your address or location">
</section>