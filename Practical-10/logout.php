<?php
// Practical 10: Secure Logout Script
session_start();

// 1. Unset all session variables
$_SESSION = [];

// 2. Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Clear remember-me cookie if present
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, "/");
}

// 4. Destroy session
session_destroy();

// 5. Redirect to login with logout notice
header("Location: login.php?msg=logged_out");
exit;
?>
