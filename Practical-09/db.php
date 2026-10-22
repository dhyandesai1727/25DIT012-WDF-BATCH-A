<?php
// Practical 9: Database Connection Helper (MySQLi)
$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed. Please ensure MySQL is running in XAMPP and 'studenthub' database exists: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
