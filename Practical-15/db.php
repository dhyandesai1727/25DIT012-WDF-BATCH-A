<?php
// Practical 15: Central Database Connection
$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed. Ensure MySQL is running in XAMPP: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

function logActivity($conn, $user_id, $username, $action, $details) {
    $uName = mysqli_real_escape_string($conn, $username);
    $act   = mysqli_real_escape_string($conn, $action);
    $det   = mysqli_real_escape_string($conn, $details);
    $ip    = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $uid   = intval($user_id);

    @mysqli_query($conn, "INSERT INTO audit_logs (user_id, username, action, details, ip_address) VALUES ($uid, '$uName', '$act', '$det', '$ip')");
}
?>
