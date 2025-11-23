<?php
require "../config/db.php"; // your DB connection

// Fetch the 5 most recent reports
$recentStmt = $conn->prepare("
    SELECT r.*, u.name, u.surname 
    FROM reports r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC 
    LIMIT 5
");
$recentStmt->execute();
$recentReports = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<nav>
    <div class="logo">
        <img src="../assets/img/ds-icon.png" alt="site icon">
        <h2>CityCare</h2>
    </div>

    <ul>
        <!-- Dashboard only for logged-in users -->
        <?php if (isset($_SESSION['role'])): ?>
            <li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="../admin/admin_dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a href="../user/my_reports.php">My Reports</a>
                <?php endif; ?>
            </li>
        <?php endif; ?>

        <li><a href="#">View All Reports</a></li>
        <li><a href="#">Common Issues</a></li>
    </ul>

    <div class="nav-buttons">
        <!-- If NOT logged in → show Sign In -->
        <?php if (!isset($_SESSION['role'])): ?>
            <a href="register.php">Sign In</a>
        <?php else: ?>
            <!-- If logged in → show Logout -->
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        <?php endif; ?>

        <a href="report_issues.php">Report Now</a>
    </div>
</nav>
