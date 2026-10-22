<?php
// Practical 12: Database Connection Configuration (MySQLi)
$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed. Ensure MySQL is running in XAMPP: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
