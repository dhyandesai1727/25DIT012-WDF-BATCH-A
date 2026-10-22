<?php
// Practical 10: Secure Login Processing with Password Verification & RBAC

session_start();
require_once 'db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_student.php");
    }
    exit;
}

$errorMessage = "";
$infoMessage = "";

// Check URL status notices
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'logged_out') {
        $infoMessage = "You have been logged out successfully.";
    } elseif ($_GET['msg'] === 'timeout') {
        $errorMessage = "Your session expired due to 15 minutes of inactivity. Please log in again.";
    } elseif ($_GET['msg'] === 'unauthorized') {
        $errorMessage = "Please log in first to access the dashboard.";
    }
}

// Check Remember-Me Cookie
$rememberedUser = isset($_COOKIE['remember_user']) ? htmlspecialchars($_COOKIE['remember_user']) : '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginInput = trim($_POST['login_input'] ?? '');
    $password   = $_POST['password'] ?? '';
    $remember   = isset($_POST['remember']);

    if (empty($loginInput) || empty($password)) {
        $errorMessage = "Both username/email and password are required.";
    } else {
        $cleanInput = mysqli_real_escape_string($conn, $loginInput);

        // Fetch user by username or email
        $query = "SELECT * FROM users WHERE (username = '$cleanInput' OR email = '$cleanInput') AND status = 'active' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password using password_verify()
            if (password_verify($password, $user['password_hash'])) {
                
                // Regenerate session ID to prevent Session Fixation
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['username']      = $user['username'];
                $_SESSION['full_name']     = $user['full_name'];
                $_SESSION['email']         = $user['email'];
                $_SESSION['role']          = $user['role'];
                $_SESSION['last_activity'] = time();

                // Handle Remember-Me Cookie
                if ($remember) {
                    // Set cookie valid for 7 days
                    setcookie('remember_user', $user['username'], time() + (7 * 24 * 60 * 60), "/");
                } else {
                    if (isset($_COOKIE['remember_user'])) {
                        setcookie('remember_user', '', time() - 3600, "/");
                    }
                }

                // Role-based redirection
                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_student.php");
                }
                exit;

            } else {
                $errorMessage = "Invalid password. Please check your credentials.";
            }
        } else {
            $errorMessage = "User account not found or inactive.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 10 - Secure StudentHub Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <h1>Sign In to StudentHub</h1>
    <p class="subtitle">Practical 10: Role-Based Authentication & Session Security</p>

    <?php if (!empty($infoMessage)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($infoMessage); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="login_input">Username or University Email *</label>
            <input type="text" id="login_input" name="login_input" value="<?php echo htmlspecialchars($_POST['login_input'] ?? $rememberedUser); ?>" required placeholder="e.g. rahul12 or admin">
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>

        <div class="form-group">
            <label style="font-weight: normal; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="remember" <?php echo !empty($rememberedUser) ? 'checked' : ''; ?>>
                Remember username on this device
            </label>
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px; border-radius: 6px; font-size: 12px; margin-top: 18px; color: #1e3a8a;">
        <strong>Demo Login Credentials:</strong><br>
        &bull; <strong>Admin:</strong> Username: <code>admin</code> | Password: <code>admin123</code><br>
        &bull; <strong>Student:</strong> Register a new student account in Practical 9 or log in with your created student account.
    </div>

    <div class="footer-text">
        Need to register? <a href="../Practical-09/register.php">Create an Account</a>
    </div>
</div>

</body>
</html>
