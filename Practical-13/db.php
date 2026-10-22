<?php
// Practical 13: Database Connection Configuration (MySQLi)
$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    header("Content-Type: application/json");
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");
?>
