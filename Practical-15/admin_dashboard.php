<?php
// Practical 15: Admin Dashboard
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    // Fallback demo admin session
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['full_name'] = 'Administrator';
    $_SESSION['role'] = 'admin';
}

$studentsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students"))['c'] ?? 0;
$eventsCount   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM events"))['c'] ?? 0;
$regCount      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations"))['c'] ?? 0;

$auditLogs = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel - Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav-wrap">
        <a href="index.php" class="brand">StudentHub Admin Console</a>
        <ul class="nav-links">
            <li><a href="index.php">Portal Home</a></li>
            <li><a href="admin_dashboard.php" class="active">Overview</a></li>
            <li><a href="student_crud.php">Manage Students</a></li>
            <li><a href="event_crud.php">Manage Events</a></li>
            <li><a href="ajax_events.html">AJAX CRUD</a></li>
            <li><a href="logout.php" class="btn-nav-logout">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <h1 style="color: #1e3a8a; margin-bottom: 20px;">Administrator Overview</h1>

    <div class="grid-3" style="margin-bottom: 25px;">
        <div class="card" style="border-left: 5px solid #2563eb;">
            <div style="font-size: 13px; color: #64748b; font-weight: bold;">TOTAL STUDENTS</div>
            <div style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $studentsCount; ?></div>
            <a href="student_crud.php" style="font-size: 13px; color: #2563eb; font-weight: bold;">Open Student CRUD &rarr;</a>
        </div>
        <div class="card" style="border-left: 5px solid #f59e0b;">
            <div style="font-size: 13px; color: #64748b; font-weight: bold;">TOTAL CAMPUS EVENTS</div>
            <div style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $eventsCount; ?></div>
            <a href="event_crud.php" style="font-size: 13px; color: #2563eb; font-weight: bold;">Open Event CRUD &rarr;</a>
        </div>
        <div class="card" style="border-left: 5px solid #10b981;">
            <div style="font-size: 13px; color: #64748b; font-weight: bold;">REGISTRATIONS</div>
            <div style="font-size: 32px; font-weight: bold; color: #1e3a8a; margin: 4px 0;"><?php echo $regCount; ?></div>
            <a href="ajax_events.html" style="font-size: 13px; color: #2563eb; font-weight: bold;">Open Live AJAX CRUD &rarr;</a>
        </div>
    </div>

    <!-- Recent System Audit Trail -->
    <div class="card">
        <h2>System Audit & Security Trail (audit_logs)</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($auditLogs && mysqli_num_rows($auditLogs) > 0): ?>
                        <?php while ($log = mysqli_fetch_assoc($auditLogs)): ?>
                            <tr>
                                <td>#<?php echo $log['id']; ?></td>
                                <td><small><?php echo htmlspecialchars($log['created_at']); ?></small></td>
                                <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                                <td><span class="badge" style="background:#fee2e2; color:#991b1b;"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['details']); ?></td>
                                <td><code><?php echo htmlspecialchars($log['ip_address']); ?></code></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;">No audit records.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2026 StudentHub Admin System | CHARUSAT FTE</p>
</footer>

</body>
</html>
