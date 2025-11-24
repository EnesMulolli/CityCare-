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
<nav>
    <div class="logo">
        <img src="../assets/img/ds-icon.png" alt="site icon">
        <h2>CityCare</h2>
    </div>

    <ul>
        <!-- Dashboard only for logged-in users -->
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
            <!-- shown to guests -->
            <li><a href="all_reports.php">View All Reports</a></li>
            <li><a href="common_issues.php">Common Issues</a></li>
        <?php endif; ?>
    </ul>

    <div class="nav-buttons">
        <!-- If NOT logged in → show Sign In -->
        <?php if (!$logged): ?>
            <a href="login.php" class="login-btn">Sign In</a>
        <?php else: ?>
            <!-- If logged in → show username (optional) and Logout -->
            <?php if ($username): ?>
                <span class="nav-username" style="color:#fff; margin-right:10px; font-weight:600;">
                    <?= htmlspecialchars($username) ?>
                </span>
            <?php endif; ?>
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        <?php endif; ?>

        <a href="report_issues.php" class="report-btn">Report Now</a>
    </div>
</nav>
