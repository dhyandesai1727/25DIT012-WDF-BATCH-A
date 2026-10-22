<?php
// Practical 15: Registration Gateway
session_start();
require_once 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim(htmlspecialchars($_POST['username'] ?? ''));
    $fullName  = trim(htmlspecialchars($_POST['full_name'] ?? ''));
    $email     = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $mobile    = trim(htmlspecialchars($_POST['mobile'] ?? ''));
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($fullName) || empty($email) || empty($mobile) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $cleanUser  = mysqli_real_escape_string($conn, $username);
        $cleanEmail = mysqli_real_escape_string($conn, $email);

        $dup = mysqli_query($conn, "SELECT id FROM users WHERE username = '$cleanUser' OR email = '$cleanEmail' LIMIT 1");
        if ($dup && mysqli_num_rows($dup) > 0) {
            $errors[] = "Username or email is already registered.";
        } else {
            $cleanName = mysqli_real_escape_string($conn, $fullName);
            $cleanMob  = mysqli_real_escape_string($conn, $mobile);
            $hash      = password_hash($password, PASSWORD_DEFAULT);

            $ins = "INSERT INTO users (username, full_name, email, mobile, password_hash, role)
                    VALUES ('$cleanUser', '$cleanName', '$cleanEmail', '$cleanMob', '$hash', 'student')";

            if (mysqli_query($conn, $ins)) {
                $newId = mysqli_insert_id($conn);
                logActivity($conn, $newId, $cleanUser, 'REGISTER', "New student account created: $cleanName");
                header("Location: login.php?msg=registered");
                exit;
            } else {
                $errors[] = "Database insert error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; text-align: center; margin-bottom: 6px;">Create Student Account</h2>
    <p style="text-align: center; color: #64748b; font-size: 13px; margin-bottom: 20px;">Join StudentHub Campus Portal</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" id="username" name="username" required placeholder="Choose a unique username">
        </div>

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" required placeholder="e.g. Rahul Patel">
        </div>

        <div class="form-group">
            <label for="email">University Email *</label>
            <input type="email" id="email" name="email" required placeholder="name@charusat.edu.in">
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="tel" id="mobile" name="mobile" required placeholder="10-digit number" maxlength="10">
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required placeholder="Min 6 characters">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password *</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-type password">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">Register Account</button>
    </form>

    <div style="text-align: center; margin-top: 15px; font-size: 13px;">
        Already registered? <a href="login.php" style="color: #2563eb; font-weight: bold;">Sign In here</a> | 
        <a href="index.php" style="color: #64748b;">Home</a>
    </div>
</div>

</body>
</html>
