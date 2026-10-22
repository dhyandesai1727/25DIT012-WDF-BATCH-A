<?php
// Practical 15: Login Gateway
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php'));
    exit;
}

$error = '';
$msg = $_GET['msg'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginInput = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($loginInput) || empty($password)) {
        $error = "Please fill in all credentials.";
    } else {
        $cleanInput = mysqli_real_escape_string($conn, $loginInput);
        $res = mysqli_query($conn, "SELECT * FROM users WHERE (username = '$cleanInput' OR email = '$cleanInput') AND status = 'active' LIMIT 1");

        if ($res && mysqli_num_rows($res) === 1) {
            $user = mysqli_fetch_assoc($res);
            if (password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['last_activity'] = time();

                logActivity($conn, $user['id'], $user['username'], 'LOGIN_SUCCESS', 'Logged in to StudentHub');

                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid password. Please check your credentials.";
            }
        } else {
            $error = "Account not found or inactive.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - StudentHub (Practical 15)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; text-align: center; margin-bottom: 6px;">Sign In to StudentHub</h2>
    <p style="text-align: center; color: #64748b; font-size: 13px; margin-bottom: 20px;">Role-Based Student & Faculty Portal Access</p>

    <?php if ($msg === 'registered'): ?>
        <div class="alert alert-success">Registration successful! Please sign in below.</div>
    <?php elseif ($msg === 'logged_out'): ?>
        <div class="alert alert-success">You have been signed out successfully.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username or University Email</label>
            <input type="text" id="username" name="username" required placeholder="admin or student email">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter password">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">Log In</button>
    </form>

    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 10px; border-radius: 6px; font-size: 12px; margin-top: 15px;">
        <strong>Demo Login Credentials:</strong><br>
        &bull; <strong>Admin:</strong> <code>admin</code> / <code>admin123</code><br>
        &bull; <strong>Student:</strong> <code>rahul12</code> / <code>student123</code>
    </div>

    <div style="text-align: center; margin-top: 15px; font-size: 13px;">
        Don't have an account? <a href="register.php" style="color: #2563eb; font-weight: bold;">Register here</a> | 
        <a href="index.php" style="color: #64748b;">Back to Home</a>
    </div>
</div>

</body>
</html>
