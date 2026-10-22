<?php
// Practical 10: Admin Dashboard (Protected Route)
require_once 'auth_check.php';

// Strict RBAC: Only Admin can access
requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; border: 1px solid #cbd5e1; }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
        .btn-logout { background: #dc2626; color: white; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .btn-logout:hover { background: #b91c1c; }
        .badge-admin { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; padding: 18px; border-radius: 6px; margin-top: 15px; }
        .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px; }
        .stat-card { background: #eff6ff; padding: 15px; border-radius: 6px; text-align: center; border: 1px solid #bfdbfe; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-bar">
        <div>
            <h2 style="color: #1e3a8a;">StudentHub Admin Control</h2>
            <span class="badge-admin">Role: Administrator</span>
        </div>
        <div>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! &nbsp;</span>
            <a href="logout.php" class="btn-logout">Sign Out</a>
        </div>
    </div>

    <div class="card">
        <h3 style="color: #1e3a8a; margin-bottom: 10px;">Admin Authentication & Session Details</h3>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Admin Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Regenerated Session ID:</strong> <code><?php echo session_id(); ?></code></p>
        <p><strong>Last Activity:</strong> <?php echo date("Y-m-d H:i:s", $_SESSION['last_activity']); ?></p>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <h4>Role Privilege</h4>
            <p style="font-size: 20px; font-weight: bold; color: #1e3a8a; margin-top: 5px;">FULL ADMIN</p>
        </div>
        <div class="stat-card">
            <h4>Session Timeout</h4>
            <p style="font-size: 20px; font-weight: bold; color: #16a34a; margin-top: 5px;">15 MINS</p>
        </div>
        <div class="stat-card">
            <h4>Security Mode</h4>
            <p style="font-size: 20px; font-weight: bold; color: #d97706; margin-top: 5px;">SECURE RBAC</p>
        </div>
    </div>

    <div class="card" style="margin-top: 25px;">
        <h3 style="color: #1e3a8a;">Next Modules in Curriculum</h3>
        <ul style="padding-left: 20px; margin-top: 10px; line-height: 1.8; font-size: 14px;">
            <li><strong>Practical 11:</strong> Student Management CRUD (Search, Filter, Edit, Delete).</li>
            <li><strong>Practical 12:</strong> Event Management CRUD with Poster Uploads.</li>
            <li><strong>Practical 14:</strong> Comprehensive Admin Dashboard with Audit Logging.</li>
        </ul>
    </div>
</div>

</body>
</html>
