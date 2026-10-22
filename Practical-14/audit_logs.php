<?php
// Practical 14: Comprehensive Audit Log Inspection Table
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['full_name'] = 'Administrator (Demo)';
    $_SESSION['role'] = 'admin';
}

// Log inspection
logAuditAction($conn, $_SESSION['user_id'], $_SESSION['username'], 'AUDIT_INSPECT', 'Viewed comprehensive audit trail logs');

$logsRes = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 50");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - StudentHub Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-wrapper">
        <a href="admin_dashboard.php" class="logo">StudentHub Admin</a>
        <nav>
            <ul class="nav-menu">
                <li><a href="admin_dashboard.php">Overview</a></li>
                <li><a href="../Practical-11/index.php">Manage Students</a></li>
                <li><a href="../Practical-12/index.php">Manage Events</a></li>
                <li><a href="audit_logs.php" class="active">Audit Logs</a></li>
                <li><a href="../Practical-10/logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="color: #1e3a8a; font-size: 24px;">System Audit & Traceability Log</h1>
            <p style="color: #64748b; font-size: 14px;">Records critical administrative events, logins, modifications, and timestamps.</p>
        </div>
        <div>
            <a href="admin_dashboard.php" style="color: #2563eb; font-weight: bold; font-size: 14px;">&larr; Back to Dashboard</a>
        </div>
    </div>

    <div class="card-box">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action Performed</th>
                        <th>Details / Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($logsRes && mysqli_num_rows($logsRes) > 0): ?>
                        <?php while ($log = mysqli_fetch_assoc($logsRes)): ?>
                            <tr>
                                <td>#<?php echo $log['id']; ?></td>
                                <td><?php echo htmlspecialchars($log['created_at']); ?></td>
                                <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                                <td><span class="badge badge-admin"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['details']); ?></td>
                                <td><code><?php echo htmlspecialchars($log['ip_address']); ?></code></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 25px;">No audit records available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>
