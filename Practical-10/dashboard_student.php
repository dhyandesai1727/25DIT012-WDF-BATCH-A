<?php
// Practical 10: Student Dashboard (Protected Route)
require_once 'auth_check.php';

// Ensure user has student role
requireRole('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; border: 1px solid #cbd5e1; }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
        .btn-logout { background: #dc2626; color: white; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .btn-logout:hover { background: #b91c1c; }
        .badge { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; padding: 18px; border-radius: 6px; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-bar">
        <div>
            <h2 style="color: #1e3a8a;">StudentHub Portal</h2>
            <span class="badge">Role: Student</span>
        </div>
        <div>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! &nbsp;</span>
            <a href="logout.php" class="btn-logout">Sign Out</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'forbidden'): ?>
        <div class="alert alert-error">Access denied: You do not have administrator permissions to access that page.</div>
    <?php endif; ?>

    <div class="card">
        <h3 style="color: #1e3a8a; margin-bottom: 10px;">Authenticated Session Overview</h3>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Registered Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Session ID:</strong> <code><?php echo session_id(); ?></code></p>
        <p><strong>Last Activity:</strong> <?php echo date("Y-m-d H:i:s", $_SESSION['last_activity']); ?></p>
        <p><strong>Inactivity Timeout:</strong> 15 Minutes (900 seconds)</p>
    </div>

    <div class="card" style="margin-top: 20px;">
        <h3 style="color: #1e3a8a; margin-bottom: 10px;">Campus Activities & Enrollment</h3>
        <p>You are authenticated into the student portal. You can view departmental notices and register for college hackathons.</p>
        <p style="margin-top: 12px; font-size: 13px; color: #64748b;">Notice: To test role-based access control, attempt to navigate to <code>dashboard_admin.php</code> in your address bar; the system will block unauthorized access and bounce you back here.</p>
    </div>
</div>

</body>
</html>
