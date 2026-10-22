<?php
// Practical 12: Delete Event and Remove Poster Image
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 1. Fetch poster image filename to delete from disk
    $sel = mysqli_query($conn, "SELECT poster_image FROM events WHERE id = $id LIMIT 1");
    if ($sel && mysqli_num_rows($sel) > 0) {
        $event = mysqli_fetch_assoc($sel);
        $posterFile = __DIR__ . "/uploads/" . $event['poster_image'];
        if (!empty($event['poster_image']) && file_exists($posterFile)) {
            @unlink($posterFile);
        }
    }

    // 2. Delete database row
    $deleteSql = "DELETE FROM events WHERE id = $id";
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
