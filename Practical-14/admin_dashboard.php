<?php
// Practical 14: Admin Dashboard with RBAC, Dynamic Menu & Audit Log
session_start();
require_once 'db.php';

// Auto-authenticate as demo admin if not set, or enforce admin check
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['full_name'] = 'Administrator (Demo)';
    $_SESSION['role'] = 'admin';
}

// Log Dashboard View in Audit Log
logAuditAction($conn, $_SESSION['user_id'], $_SESSION['username'], 'DASHBOARD_VIEW', 'Accessed primary admin metrics dashboard');

// 1. SQL Aggregations for Statistics
$studentCountRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM students");
$studentCount = $studentCountRes ? mysqli_fetch_assoc($studentCountRes)['cnt'] : 0;

$eventCountRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM events");
$eventCount = $eventCountRes ? mysqli_fetch_assoc($eventCountRes)['cnt'] : 0;

$regCountRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM registrations");
$regCount = $regCountRes ? mysqli_fetch_assoc($regCountRes)['cnt'] : 0;

$userCountRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM users WHERE role = 'student'");
$userCount = $userCountRes ? mysqli_fetch_assoc($userCountRes)['cnt'] : 0;

// 2. Fetch Latest Registrations with JOIN
$recentRegQuery = "SELECT r.id, s.full_name AS student_name, s.roll_number, s.department, e.title AS event_title, r.registered_at, r.attendance_status 
                   FROM registrations r
                   JOIN students s ON r.student_id = s.id
                   JOIN events e ON r.event_id = e.id
                   ORDER BY r.registered_at DESC LIMIT 5";
$recentRegRes = mysqli_query($conn, $recentRegQuery);

// 3. Fetch Recent Audit Logs (Last 5)
$auditQuery = "SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 5";
$auditRes = mysqli_query($conn, $auditQuery);

$userRole = $_SESSION['role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 14 - Admin Control Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Dynamic Menu based on Role -->
    <header>
        <div class="header-wrapper">
            <a href="admin_dashboard.php" class="logo">StudentHub Admin</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="admin_dashboard.php" class="active">Overview</a></li>
                    
                    <?php if ($userRole === 'admin'): ?>
                        <!-- Admin-Only Dynamic Menu Items -->
                        <li><a href="../Practical-11/index.php">Manage Students</a></li>
                        <li><a href="../Practical-12/index.php">Manage Events</a></li>
                        <li><a href="../Practical-13/index.html">AJAX CRUD</a></li>
                        <li><a href="audit_logs.php">Audit Logs</a></li>
                    <?php else: ?>
                        <!-- Student Menu Items -->
                        <li><a href="../Practical-03/events.html">Browse Events</a></li>
                        <li><a href="../Practical-10/dashboard_student.php">My Profile</a></li>
                    <?php endif; ?>

                    <li><a href="../Practical-10/logout.php" class="btn-logout">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
            <div>
                <h1 style="color: #1e3a8a; font-size: 24px;">Administrative Dashboard</h1>
                <p style="color: #64748b; font-size: 14px;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong> (Role: <span class="badge badge-admin"><?php echo strtoupper($userRole); ?></span>)</p>
            </div>
            <div>
                <a href="audit_logs.php" style="color: #2563eb; font-weight: bold; font-size: 14px; text-decoration: underline;">View Complete Audit Trail &rarr;</a>
            </div>
        </div>

        <!-- 4 Aggregated Metric Cards -->
        <div class="metrics-grid">
            <div class="metric-card students">
                <div class="metric-title">Enrolled Students</div>
                <div class="metric-number"><?php echo $studentCount; ?></div>
                <div class="metric-sub">&uarr; Active department records</div>
            </div>

            <div class="metric-card events">
                <div class="metric-title">Campus Events</div>
                <div class="metric-number"><?php echo $eventCount; ?></div>
                <div class="metric-sub">&uarr; Hackathons, workshops & fests</div>
            </div>

            <div class="metric-card registrations">
                <div class="metric-title">Total Registrations</div>
                <div class="metric-number"><?php echo $regCount; ?></div>
                <div class="metric-sub">&uarr; Student event enrollments</div>
            </div>

            <div class="metric-card users">
                <div class="metric-title">Student Accounts</div>
                <div class="metric-number"><?php echo $userCount; ?></div>
                <div class="metric-sub">&uarr; Registered login credentials</div>
            </div>
        </div>

        <!-- Dashboard Layout: 2-Columns -->
        <div class="dashboard-grid">
            <!-- Column 1: Latest Registrations -->
            <div class="card-box">
                <h2>Latest Student Event Registrations (SQL JOIN)</h2>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Roll No</th>
                                <th>Event Enrolled</th>
                                <th>Registered At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentRegRes && mysqli_num_rows($recentRegRes) > 0): ?>
                                <?php while ($reg = mysqli_fetch_assoc($recentRegRes)): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($reg['student_name']); ?></strong></td>
                                        <td><code><?php echo htmlspecialchars($reg['roll_number']); ?></code></td>
                                        <td><?php echo htmlspecialchars($reg['event_title']); ?></td>
                                        <td><small><?php echo htmlspecialchars($reg['registered_at']); ?></small></td>
                                        <td><span class="badge badge-success"><?php echo htmlspecialchars($reg['attendance_status']); ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 20px;">No event registrations found in database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Column 2: Recent Audit Trail Activity -->
            <div class="card-box">
                <h2>Recent Audit Log Activity</h2>
                <ul style="list-style: none; padding: 0;">
                    <?php if ($auditRes && mysqli_num_rows($auditRes) > 0): ?>
                        <?php while ($log = mysqli_fetch_assoc($auditRes)): ?>
                            <li style="border-bottom: 1px solid #f1f5f9; padding: 8px 0; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <strong><?php echo htmlspecialchars($log['action']); ?></strong>
                                    <small style="color: #64748b;"><?php echo htmlspecialchars($log['created_at']); ?></small>
                                </div>
                                <div style="color: #475569; margin-top: 2px;">
                                    <?php echo htmlspecialchars($log['details']); ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li style="color: #64748b; font-size: 13px;">No audit events recorded yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>

</body>
</html>
