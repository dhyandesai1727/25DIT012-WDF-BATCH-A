<?php
// Practical 8: Database Connection Configuration (MySQLi Procedural)

$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";
$port     = 3306;

// Create MySQL connection
$conn = mysqli_connect($host, $username, $password, $database, $port);

// Check connection
if (!$conn) {
    die("Database Connection Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error());
}

// Set character set to UTF-8
mysqli_set_charset($conn, "utf8mb4");
?>
