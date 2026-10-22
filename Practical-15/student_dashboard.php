<?php
// Practical 15: Student Dashboard
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION;
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav-wrap">
        <a href="index.php" class="brand">StudentHub FTE</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="student_dashboard.php" class="active">Dashboard</a></li>
            <li><a href="ajax_events.html">Live Events</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="logout.php" class="btn-nav-logout">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 25px;">
        <h1 style="color: #1e3a8a; margin-bottom: 6px;">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
        <p style="color: #64748b; font-size: 14px;">Username: <strong><?php echo htmlspecialchars($user['username']); ?></strong> | Role: <span class="badge" style="background:#dbeafe; color:#1e40af;">Student</span></p>
    </div>

    <h2 style="color: #1e3a8a; margin-bottom: 15px;">Available Campus Events</h2>
    <div class="grid-3">
        <?php while ($ev = mysqli_fetch_assoc($events)): ?>
            <div class="card">
                <span class="badge" style="background:#e0f2fe; color:#0369a1;"><?php echo htmlspecialchars($ev['category']); ?></span>
                <h3 style="margin-top: 8px;"><?php echo htmlspecialchars($ev['title']); ?></h3>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
                    Date: <?php echo htmlspecialchars($ev['event_date']); ?> | Venue: <?php echo htmlspecialchars($ev['venue']); ?>
                </p>
                <p style="font-size: 13px; color: #334155; margin-bottom: 12px;">
                    <?php echo htmlspecialchars($ev['description'] ?? ''); ?>
                </p>
                <button class="btn btn-primary" onclick="alert('Enrolled in <?php echo htmlspecialchars(addslashes($ev['title'])); ?>! A confirmation notification has been sent.');" style="font-size: 12px; width: 100%;">Enroll Now</button>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<footer>
    <p>&copy; 2026 StudentHub Integrated Portal</p>
</footer>

</body>
</html>
