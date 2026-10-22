<?php
// Practical 14: Database Connection Helper (MySQLi)
$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed. Ensure MySQL is running in XAMPP: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Helper function to insert an audit log
function logAuditAction($conn, $userId, $username, $action, $details) {
    $cleanUser = mysqli_real_escape_string($conn, $username);
    $cleanAct  = mysqli_real_escape_string($conn, $action);
    $cleanDet  = mysqli_real_escape_string($conn, $details);
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    $sql = "INSERT INTO audit_logs (user_id, username, action, details, ip_address)
            VALUES ($userId, '$cleanUser', '$cleanAct', '$cleanDet', '$ip')";
    @mysqli_query($conn, $sql);
}
?>
