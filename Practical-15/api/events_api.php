<?php
// Practical 15: REST JSON API for Events
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
require_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $res = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
    $data = [];
    while ($r = mysqli_fetch_assoc($res)) { $data[] = $r; }
    echo json_encode(["status" => "success", "data" => $data]);
    exit;
}

if ($method === 'POST') {
    $in = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    $action = $in['action'] ?? '';

    if ($action === 'add') {
        $title = mysqli_real_escape_string($conn, trim($in['title'] ?? ''));
        $cat   = mysqli_real_escape_string($conn, trim($in['category'] ?? 'Technical'));
        $date  = mysqli_real_escape_string($conn, trim($in['event_date'] ?? ''));
        $venue = mysqli_real_escape_string($conn, trim($in['venue'] ?? ''));
        $coord = mysqli_real_escape_string($conn, trim($in['coordinator'] ?? 'Faculty'));

        if (empty($title) || empty($date)) {
            echo json_encode(["status" => "error", "message" => "Title and date are required."]);
            exit;
        }

        $sql = "INSERT INTO events (title, category, event_date, event_time, venue, coordinator) VALUES ('$title', '$cat', '$date', '10:00:00', '$venue', '$coord')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Event created via AJAX!"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = intval($in['id'] ?? 0);
        if ($id > 0 && mysqli_query($conn, "DELETE FROM events WHERE id = $id")) {
            echo json_encode(["status" => "success", "message" => "Event deleted via AJAX!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete event."]);
        }
        exit;
    }
}
?>
