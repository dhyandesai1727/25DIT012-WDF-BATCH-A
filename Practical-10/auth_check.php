<?php
// Practical 10: Authentication, Inactivity Timeout, & Role-Based Access Control Helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=unauthorized");
    exit;
}

// 2. Session Inactivity Timeout (Default: 15 minutes = 900 seconds)
$timeoutDuration = 900;

if (isset($_SESSION['last_activity'])) {
    $inactiveTime = time() - $_SESSION['last_activity'];
    if ($inactiveTime > $timeoutDuration) {
        // Session expired
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: login.php?msg=timeout");
        exit;
    }
}

// Update last activity timestamp on every valid request
$_SESSION['last_activity'] = time();

// 3. Helper Function to Restrict by Role
function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        // Forbidden: redirect to their authorized dashboard
        if ($_SESSION['role'] === 'student') {
            header("Location: dashboard_student.php?msg=forbidden");
        } else {
            header("Location: dashboard_admin.php?msg=forbidden");
        }
        exit;
    }
}
?>
