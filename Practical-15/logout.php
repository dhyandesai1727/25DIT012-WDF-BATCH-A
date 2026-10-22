<?php
// Practical 15: Logout
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], 'LOGOUT', 'User signed out');
}

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header("Location: login.php?msg=logged_out");
exit;
?>
