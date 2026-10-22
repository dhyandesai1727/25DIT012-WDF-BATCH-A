<?php
// Practical 11: Delete Student Record
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $deleteSql = "DELETE FROM students WHERE id = $id";
    if (mysqli_query($conn, $deleteSql)) {
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        header("Location: index.php?msg=error");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
