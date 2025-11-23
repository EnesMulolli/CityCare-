<?php
include('../includes/header.php');
?>
    <section id="topics">
    <h1>What Can You Report?</h1>
    <p>From potholes to streetlights, help us keep your city in great shape</p>

    <div class="grid">
        <div class="card">
            <h3>Road Issues</h3>
            <p>Potholes, cracks, damaged signs</p>
        </div>
        <div class="card">
            <h3>Street Lights</h3>
            <p>Broken or flickering lights</p>
        </div>
        <div class="card">
            <h3>Sanitation</h3>
            <p>Trash, litter, illegal dumping</p>
        </div>
        <div class="card">
            <h3>Safety Hazards</h3>
            <p>Dangerous conditions or hazards</p>
        </div>
        <div class="card">
            <h3>Parks & Trees</h3>
            <p>Fallen trees, park maintenance</p>
        </div>
        <div class="card">
            <h3>Water Issues</h3>
            <p>Leaks, flooding, drainage</p>
        </div>
    </div>
</section>


<section id="how-it-works">
    <div class="container">
        <h2>How It Works</h2>
        <p class="subtitle">Simple, fast, and effective civic engagement</p>
        <ol class="steps-list">
            <li>
                <span class="step-number">01</span>
                <div class="step-content">
                    <h3>Report the Issue</h3>
                    <p>Take a photo, add details, and drop a pin on the map. It takes less than 2 minutes.</p>
                </div>
            </li>
            <li>
                <span class="step-number">02</span>
                <div class="step-content">
                    <h3>We Route It</h3>
                    <p>Your report goes directly to the right department. No phone calls or paperwork needed.</p>
                </div>
            </li>
            <li>
                <span class="step-number">03</span>
                <div class="step-content">
                    <h3>Track Progress</h3>
                    <p>Get real-time updates as the city works on fixing the issue. See exactly what's happening.</p>
                </div>
            </li>
            <li>
                <span class="step-number">04</span>
                <div class="step-content">
                    <h3>Issue Resolved</h3>
                    <p>Once fixed, you'll be notified. Your neighborhood is better because you cared.</p>
                </div>
            </li>
        </ol>
    </div>
</section>


<section id="statistics">
    <div class="container">
        <h2>Making a Real Difference</h2>
        <p class="subtitle">Join thousands of citizens actively improving their neighborhoods</p>
        <div class="stats-grid">
            <div class="stat">
                <span class="number">125K+</span>
                <span class="label">Reports Submitted</span>
            </div>
            <div class="stat">
                <span class="number">89%</span>
                <span class="label">Issues Resolved</span>
            </div>
            <div class="stat">
                <span class="number">48hrs</span>
                <span class="label">Avg Response Time</span>
            </div>
            <div class="stat">
                <span class="number">50K+</span>
                <span class="label">Active Citizens</span>
            </div>
        </div>
    </div>
</section>


<?php
require "../config/db.php";

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

<div class="activity-list">
    <?php if(!empty($recentReports)): ?>
        <?php foreach($recentReports as $report): ?>
        <div class="activity-card">
            <div class="activity-header">
                <div class="user-info">
                    <!-- <div class="avatar">
                        <?= strtoupper(substr($report['name'], 0, 1) . substr($report['surname'], 0, 1)) ?>
                    </div> -->
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($report['name'] . ' ' . $report['surname']) ?></span>
                        <span class="time"><?= date("M d, Y • H:i", strtotime($report['created_at'])) ?></span>
                    </div>
                </div>
                <span class="status-badge <?= strtolower(str_replace(' ', '-', $report['status'])) ?>">
                    <?= htmlspecialchars($report['status']) ?>
                </span>
            </div>

            <div class="activity-content">
                <?php if($report['image'] && file_exists("../uploads/" . $report['image'])): ?>
                    <div class="image-container">
                        <img src="../uploads/<?= htmlspecialchars($report['image']) ?>" alt="Report Image" class="report-image clickable-image">
                        <div class="image-overlay">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9Z"/>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="text-content">
                    <h3 class="report-title"><?= htmlspecialchars($report['title']) ?></h3>
                    <div class="meta-info">
                        <span class="location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <?= htmlspecialchars($report['location']) ?>
                        </span>
                    </div>
                    <p class="description"><?= htmlspecialchars($report['description']) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10,9 9,9 8,9"/>
            </svg>
            <h3>No Recent Reports</h3>
            <p>There are no reports to display at the moment.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <img id="modalImage" src="" alt="Enlarged Report Image">
    </div>
</div>

<section class="cta-section">
    <div class="cta-content">
        <h2>Ready to Make a Difference?</h2>
        <p>
            Join your neighbors in keeping our city clean, safe, and beautiful. 
            Every report matters.
        </p>

        <div class="cta-buttons">
            <a href="#" class="btn primary">Get Started Now</a>
            <a href="#" class="btn secondary">Learn More</a>
        </div>
    </div>
</section>



<footer class="footer">
    <div class="footer-container">

        <!-- Logo + Description -->
        <div class="footer-logo">
            <img src="../assets/img/ds-icon.png" alt="CityCare Logo">
            <h2>CityCare</h2>
            <p>Empowering citizens to report issues and make their communities better, one report at a time.</p>
        </div>

        <!-- Report -->
        <div class="footer-column">
            <h3>Report</h3>
            <ul>
                <li><a href="report_issues.php">Submit Report</a></li>
                <li><a href="#">Track Status</a></li>
                <li><a href="#">Issue Types</a></li>
                <li><a href="#">Guidelines</a></li>
            </ul>
        </div>

        <!-- Resources -->
        <div class="footer-column">
            <h3>Resources</h3>
            <ul>
                <li><a href="#">How It Works</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Support</a></li>
            </ul>
        </div>

        <!-- Legal -->
        <div class="footer-column">
            <h3>Legal</h3>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Accessibility</a></li>
                <li><a href="#">Security</a></li>
            </ul>
        </div>
    </div>

    <!-- Bottom Text -->
    <div class="footer-bottom">
        © 2025 CityCare. All rights reserved.
    </div>
</footer>

<script src="../assets/js/main.js"></script>    
</body>
</html>