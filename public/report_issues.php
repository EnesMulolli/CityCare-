<?php
session_start();
require "../config/db.php";

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle success/error messages
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Issue – CityCare</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f2f5f7;
    color: #333;
}
header { background: #284b63; padding: 12px 30px; color: #fff; }
header h2 { display: inline-block; margin: 0; }
.container {
    max-width: 600px; margin: 40px auto; background: #fff;
    padding: 30px; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
h2 { text-align: center; color: #284b63; margin-bottom: 25px; }
.form-group { margin-bottom: 15px; }
label { font-weight: 600; display: block; margin-bottom: 5px; }
input, textarea, select { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d1d1; font-size: 15px; }
input:focus, textarea:focus, select:focus { border-color: #284b63; outline: none; box-shadow: 0 0 5px rgba(40, 75, 99, 0.3); }
button { padding: 14px; border: none; border-radius: 8px; background: #284b63; color: #fff; font-size: 16px; cursor: pointer; width: 100%; margin-top: 10px; }
button:hover { background: #3c6e71; }
.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
.error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
#otherInput { display: none; }
</style>
</head>
<body>

<header>
    <h2>CityCare – Report an Issue</h2>
</header>

<div class="container">
    <?php if($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php elseif($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="../api/submit_report.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category">Issue Category</label>
            <select id="category" name="title" onchange="handleCategoryChange()" required>
                <option value="">Select a category</option>
                <option value="Road Issues">Road Issues</option>
                <option value="Street Lights">Street Lights</option>
                <option value="Sanitation">Sanitation</option>
                <option value="Safety Hazards">Safety Hazards</option>
                <option value="Parks & Trees">Parks & Trees</option>
                <option value="Water Issues">Water Issues</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group" id="otherInput">
            <label for="other_text">Specify Other Issue</label>
            <input type="text" id="other_text" name="other_title" placeholder="Describe the issue type">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" required>
        </div>

        <div class="form-group">
            <label for="image">Image (optional)</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit">Submit Report</button>
    </form>
</div>

<script>
function handleCategoryChange() {
    var category = document.getElementById('category').value;
    var otherInput = document.getElementById('otherInput');

    if(category === 'Other') {
        otherInput.style.display = 'block';
        document.getElementById('other_text').required = true;
    } else {
        otherInput.style.display = 'none';
        document.getElementById('other_text').required = false;
    }
}
</script>

</body>
</html>
