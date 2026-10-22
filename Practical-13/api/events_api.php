<?php
// Practical 13: Simple PHP JSON API for Events (CRUD without page reload)

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

require_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Handle GET Requests (Read Events)
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM events WHERE id = $id LIMIT 1";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            echo json_encode(["status" => "success", "data" => mysqli_fetch_assoc($res)]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Event not found."]);
        }
    } else {
        $sql = "SELECT * FROM events ORDER BY event_date ASC";
        $res = mysqli_query($conn, $sql);
        $events = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $events[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $events]);
    }
    exit;
}

// Handle POST Requests (Create, Update, Delete)
if ($method === 'POST') {
    // Read JSON payload or form-encoded POST
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = $input['action'] ?? '';

    // 1. ADD EVENT
    if ($action === 'add') {
        $title       = trim($input['title'] ?? '');
        $category    = trim($input['category'] ?? 'Technical');
        $eventDate   = trim($input['event_date'] ?? '');
        $eventTime   = trim($input['event_time'] ?? '10:00:00');
        $venue       = trim($input['venue'] ?? '');
        $coordinator = trim($input['coordinator'] ?? 'Faculty');
        $maxSeats    = intval($input['max_seats'] ?? 100);

        if (empty($title) || empty($eventDate) || empty($venue)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Title, date, and venue are mandatory fields."]);
            exit;
        }

        $cleanTitle = mysqli_real_escape_string($conn, $title);
        $cleanCat   = mysqli_real_escape_string($conn, $category);
        $cleanDate  = mysqli_real_escape_string($conn, $eventDate);
        $cleanTime  = mysqli_real_escape_string($conn, $eventTime);
        $cleanVenue = mysqli_real_escape_string($conn, $venue);
        $cleanCoord = mysqli_real_escape_string($conn, $coordinator);

        $sql = "INSERT INTO events (title, category, event_date, event_time, venue, coordinator, max_seats)
                VALUES ('$cleanTitle', '$cleanCat', '$cleanDate', '$cleanTime', '$cleanVenue', '$cleanCoord', $maxSeats)";

        if (mysqli_query($conn, $sql)) {
            $newId = mysqli_insert_id($conn);
            echo json_encode([
                "status"  => "success",
                "message" => "Event added successfully via AJAX!",
                "id"      => $newId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        }
        exit;
    }

    // 2. UPDATE EVENT
    if ($action === 'update') {
        $id          = intval($input['id'] ?? 0);
        $title       = trim($input['title'] ?? '');
        $category    = trim($input['category'] ?? '');
        $eventDate   = trim($input['event_date'] ?? '');
        $eventTime   = trim($input['event_time'] ?? '');
        $venue       = trim($input['venue'] ?? '');
        $coordinator = trim($input['coordinator'] ?? '');
        $maxSeats    = intval($input['max_seats'] ?? 100);

        if ($id <= 0 || empty($title) || empty($eventDate)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid ID, title, or date."]);
            exit;
        }

        $cleanTitle = mysqli_real_escape_string($conn, $title);
        $cleanCat   = mysqli_real_escape_string($conn, $category);
        $cleanDate  = mysqli_real_escape_string($conn, $eventDate);
        $cleanTime  = mysqli_real_escape_string($conn, $eventTime);
        $cleanVenue = mysqli_real_escape_string($conn, $venue);
        $cleanCoord = mysqli_real_escape_string($conn, $coordinator);

        $sql = "UPDATE events SET 
                    title = '$cleanTitle',
                    category = '$cleanCat',
                    event_date = '$cleanDate',
                    event_time = '$cleanTime',
                    venue = '$cleanVenue',
                    coordinator = '$cleanCoord',
                    max_seats = $maxSeats
                WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Event updated successfully via AJAX!"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        }
        exit;
    }

    // 3. DELETE EVENT
    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid Event ID."]);
            exit;
        }

        $sql = "DELETE FROM events WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Event removed successfully via AJAX!"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        }
        exit;
    }

    echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Method not allowed."]);
?>
