<?php
// Practical 15: Contact Page
session_start();
require_once 'db.php';

$sent = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - StudentHub Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav-wrap">
        <a href="index.php" class="brand">StudentHub FTE</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="login.php">Sign In</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <h1 style="color: #1e3a8a; margin-bottom: 20px;">Contact Department of IT</h1>

    <?php if ($sent): ?>
        <div class="alert alert-success">✔ Thank you for your inquiry. A department coordinator will reply shortly.</div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <div class="card">
            <h3>Campus Location</h3>
            <p style="font-size: 14px; line-height: 1.8;">
                <strong>CHARUSAT Campus</strong><br>
                Faculty of Technology & Engineering (FTE)<br>
                Department of Information Technology<br>
                Changa, Dist. Anand, Gujarat - 388421<br>
                Email: <code>it.fte@charusat.ac.in</code><br>
                Phone: +91 2697 265011
            </p>
        </div>

        <div class="card">
            <h3>Send an Inquiry</h3>
            <form action="contact.php" method="POST">
                <div class="form-group"><label>Your Name *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Email Address *</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Message *</label><textarea name="msg" rows="3" required></textarea></div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Message</button>
            </form>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2026 StudentHub Integrated Portal</p>
</footer>

</body>
</html>
