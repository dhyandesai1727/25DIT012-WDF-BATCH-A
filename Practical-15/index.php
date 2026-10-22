<?php
// Practical 15: Master Full-Stack Integrated StudentHub Entry Portal
session_start();
require_once 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userRole   = $_SESSION['role'] ?? 'guest';
$userName   = $_SESSION['full_name'] ?? 'Guest';

// Fetch Active Events from MySQL
$eventsQuery = "SELECT * FROM events ORDER BY event_date ASC LIMIT 6";
$eventsResult = mysqli_query($conn, $eventsQuery);

// Fetch Total Counters
$stdCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students"))['c'] ?? 0;
$evCount  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM events"))['c'] ?? 0;
$regCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Integrated University Portal | Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="nav-wrap">
            <a href="index.php" class="brand">🎓 StudentHub FTE</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="#events-section">Campus Events</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                    <li><a href="contact.php">Contact</a></li>

                    <?php if ($isLoggedIn): ?>
                        <?php if ($userRole === 'admin'): ?>
                            <li><a href="admin_dashboard.php">Admin Panel</a></li>
                            <li><a href="student_crud.php">Students</a></li>
                            <li><a href="event_crud.php">Events CRUD</a></li>
                        <?php else: ?>
                            <li><a href="student_dashboard.php">My Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php" class="btn-nav-logout">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Sign In</a></li>
                        <li><a href="register.php" class="btn btn-accent" style="padding: 4px 10px;">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Hero Section -->
        <section class="hero-box">
            <h1>Faculty of Technology & Engineering Student Portal</h1>
            <p>A unified web application integrating responsive UI, secure session authentication, role-based access control, relational database CRUD, and live AJAX event updates.</p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <?php if ($isLoggedIn): ?>
                    <a href="<?php echo $userRole === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php'; ?>" class="btn btn-accent">Open My Dashboard &rarr;</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-accent">Sign In to StudentHub</a>
                    <a href="register.php" class="btn btn-primary" style="background: rgba(255,255,255,0.2); border: 1px solid white;">Create Student Account</a>
                <?php endif; ?>
                <a href="ajax_events.html" class="btn btn-secondary">Test Live AJAX CRUD &rarr;</a>
            </div>
        </section>

        <!-- Metric Summary -->
        <div class="grid-3" style="margin-bottom: 30px;">
            <div class="card" style="text-align: center; border-top: 4px solid #2563eb;">
                <h3 style="font-size: 13px; color: #64748b; text-transform: uppercase;">Total Students</h3>
                <p style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $stdCount; ?></p>
                <small style="color: #16a34a;">Active registered students</small>
            </div>
            <div class="card" style="text-align: center; border-top: 4px solid #f59e0b;">
                <h3 style="font-size: 13px; color: #64748b; text-transform: uppercase;">Active Events</h3>
                <p style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $evCount; ?></p>
                <small style="color: #16a34a;">Campus workshops & hackathons</small>
            </div>
            <div class="card" style="text-align: center; border-top: 4px solid #10b981;">
                <h3 style="font-size: 13px; color: #64748b; text-transform: uppercase;">Event Registrations</h3>
                <p style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $regCount; ?></p>
                <small style="color: #16a34a;">Verified participant enrollments</small>
            </div>
        </div>

        <!-- Campus Events Section -->
        <section id="events-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="color: #1e3a8a;">Upcoming Campus Events</h2>
                <a href="ajax_events.html" style="font-size: 13px; color: #2563eb; font-weight: bold;">View with AJAX Live Updates &rarr;</a>
            </div>

            <div class="grid-3">
                <?php if ($eventsResult && mysqli_num_rows($eventsResult) > 0): ?>
                    <?php while ($ev = mysqli_fetch_assoc($eventsResult)): ?>
                        <div class="card">
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; margin-bottom: 8px;"><?php echo htmlspecialchars($ev['category']); ?></span>
                            <h3><?php echo htmlspecialchars($ev['title']); ?></h3>
                            <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
                                📅 <strong>Date:</strong> <?php echo htmlspecialchars($ev['event_date']); ?> | 📍 <strong>Venue:</strong> <?php echo htmlspecialchars($ev['venue']); ?>
                            </p>
                            <p style="font-size: 13px; color: #334155; margin-bottom: 15px;">
                                <?php echo htmlspecialchars($ev['description'] ?? 'Join your fellow students for this campus event.'); ?>
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <small style="color: #64748b;">Coordinator: <?php echo htmlspecialchars($ev['coordinator']); ?></small>
                                <a href="login.php" class="btn btn-primary" style="font-size: 12px; padding: 4px 10px;">Register</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #64748b; grid-column: 1/-1;">No events currently listed.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 StudentHub Full-Stack Portal | CHARUSAT FTE - Web Development Frameworks (ITUE203)</p>
        <p style="margin-top: 6px;">
            <a href="faq.html">FAQs</a> | 
            <a href="contact.php">Contact Us</a> | 
            <a href="../index.html">Master Practical Hub (1 to 15)</a>
        </p>
    </footer>

</body>
</html>
