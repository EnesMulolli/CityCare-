<?php
include('navbar.php');
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

<section id="welcome">
    <h1>Make Your City Better</h1>
    <p>Report issues in your neighborhood. Track progress. See real change happen.</p>
    <div class="center-links">
        <a href="../public/report_issues.php">Report an Issue</a>
        <a href="">Track My Reports</a>
    </div>
    <input type="text" name="search-location" id="search-location" placeholder="Enter your address or location">
</section>