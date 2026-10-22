<?php
// Practical 9: Secure User Registration with Database Insert & Duplicate Email Check

require_once 'db.php';

$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize user inputs
    $username  = trim(htmlspecialchars($_POST['username'] ?? ''));
    $fullName  = trim(htmlspecialchars($_POST['full_name'] ?? ''));
    $email     = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $mobile    = trim(htmlspecialchars($_POST['mobile'] ?? ''));
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';
    $role      = 'student'; // Default role

    // Server-side validations
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    if (empty($fullName) || !preg_match("/^[a-zA-Z\s]{3,50}$/", $fullName)) {
        $errors[] = "Full Name must contain only letters and spaces (min 3 chars).";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid university email address is required.";
    }

    if (empty($mobile) || !preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = "Mobile number must be 10 digits starting with 6, 7, 8, or 9.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check for duplicate Email and Username
    if (empty($errors)) {
        $cleanEmail = mysqli_real_escape_string($conn, $email);
        $cleanUser  = mysqli_real_escape_string($conn, $username);

        $checkQuery = "SELECT id, email, username FROM users WHERE email = '$cleanEmail' OR username = '$cleanUser' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $existing = mysqli_fetch_assoc($checkResult);
            if ($existing['email'] === $email) {
                $errors[] = "This email is already registered. Please sign in or use a different email.";
            }
            if ($existing['username'] === $username) {
                $errors[] = "This username is already taken. Please choose another username.";
            }
        }
    }

    // Insert user into database with password_hash()
    if (empty($errors)) {
        // Secure BCRYPT hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $cleanFullName = mysqli_real_escape_string($conn, $fullName);
        $cleanMobile   = mysqli_real_escape_string($conn, $mobile);

        $insertQuery = "INSERT INTO users (username, full_name, email, mobile, password_hash, role) 
                        VALUES ('$cleanUser', '$cleanFullName', '$cleanEmail', '$cleanMobile', '$hashedPassword', '$role')";

        if (mysqli_query($conn, $insertQuery)) {
            $successMessage = "Account registered successfully! You can now log in.";
        } else {
            $errors[] = "Database insert error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 9 - Secure User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-card">
    <h1>StudentHub Registration</h1>
    <p class="subtitle">Practical 9: Secure Registration with MySQLi, Duplicate Email Check & Password Hashing</p>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <strong>Success:</strong> <?php echo $successMessage; ?>
            <div style="margin-top: 10px;">
                <a href="login.php" style="color: #15803d; font-weight: bold; text-decoration: underline;">Click here to Log In &rarr;</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Registration could not be completed:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validateFrontend();">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required placeholder="e.g. rahul12">
        </div>

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required placeholder="e.g. Rahul Patel">
        </div>

        <div class="form-group">
            <label for="email">University Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required placeholder="e.g. rahul@charusat.edu.in">
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number (10 digits) *</label>
            <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>" required placeholder="e.g. 9876543210" maxlength="10">
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required placeholder="Min 6 characters">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password *</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter password">
        </div>

        <button type="submit" class="btn-submit">Create Student Account</button>
    </form>

    <div class="footer-text">
        Already registered? <a href="login.php">Sign In</a>
    </div>
</div>

<script>
// Simple Frontend Validation Script
function validateFrontend() {
    const name = document.getElementById("full_name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;

    if (!/^[a-zA-Z\s]{3,50}$/.test(name)) {
        alert("Full Name must contain only letters and spaces (min 3 chars).");
        return false;
    }
    if (!/^[6-9]\d{9}$/.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        return false;
    }
    if (pass.length < 6) {
        alert("Password must be at least 6 characters.");
        return false;
    }
    if (pass !== confirm) {
        alert("Passwords do not match.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
