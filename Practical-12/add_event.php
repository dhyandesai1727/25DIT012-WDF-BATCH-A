<?php
// Practical 12: Add Event with Poster File Upload & Validation
require_once 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim(htmlspecialchars($_POST['title']));
    $category    = trim(htmlspecialchars($_POST['category']));
    $eventDate   = trim(htmlspecialchars($_POST['event_date']));
    $eventTime   = trim(htmlspecialchars($_POST['event_time']));
    $venue       = trim(htmlspecialchars($_POST['venue']));
    $coordinator = trim(htmlspecialchars($_POST['coordinator']));
    $maxSeats    = intval($_POST['max_seats']);
    $description = trim(htmlspecialchars($_POST['description']));
    $posterName  = "";

    if (empty($title) || empty($eventDate) || empty($venue)) {
        $errors[] = "Title, Event Date, and Venue are required.";
    }

    // --- File Upload Processing & Validation ---
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['poster']['tmp_name'];
        $originalName  = $_FILES['poster']['name'];
        $fileSize      = $_FILES['poster']['size'];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // 1. Validate File Size (Max 2MB = 2 * 1024 * 1024 bytes)
        $maxSizeBytes = 2 * 1024 * 1024;
        if ($fileSize > $maxSizeBytes) {
            $errors[] = "Poster image must be smaller than 2MB in size.";
        }

        // 2. Validate Allowed File Extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF images are permitted.";
        }

        // 3. Move uploaded file if valid
        if (empty($errors)) {
            // Generate unique, safe filename to prevent overwriting
            $newFileName = "event_" . time() . "_" . rand(1000, 9999) . "." . $fileExtension;
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $targetFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                $posterName = $newFileName;
            } else {
                $errors[] = "Failed to move uploaded poster to destination folder.";
            }
        }
    }

    // Insert into MySQL
    if (empty($errors)) {
        $cleanTitle  = mysqli_real_escape_string($conn, $title);
        $cleanCat    = mysqli_real_escape_string($conn, $category);
        $cleanDate   = mysqli_real_escape_string($conn, $eventDate);
        $cleanTime   = mysqli_real_escape_string($conn, $eventTime);
        $cleanVenue  = mysqli_real_escape_string($conn, $venue);
        $cleanCoord  = mysqli_real_escape_string($conn, $coordinator);
        $cleanDesc   = mysqli_real_escape_string($conn, $description);
        $cleanPoster = mysqli_real_escape_string($conn, $posterName);

        $sql = "INSERT INTO events (title, category, event_date, event_time, venue, coordinator, max_seats, description, poster_image)
                VALUES ('$cleanTitle', '$cleanCat', '$cleanDate', '$cleanTime', '$cleanVenue', '$cleanCoord', $maxSeats, '$cleanDesc', '$cleanPoster')";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=added");
            exit;
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Practical 12</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; margin-bottom: 6px;">Create Campus Event</h2>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Upload poster flyer and register event into database</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="add_event.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Event Title *</label>
            <input type="text" id="title" name="title" required placeholder="e.g. Hackathon 2026">
        </div>

        <div class="form-group">
            <label for="category">Category *</label>
            <select id="category" name="category" required>
                <option value="Technical">Technical</option>
                <option value="Cultural">Cultural</option>
                <option value="Sports">Sports</option>
                <option value="Workshop">Workshop</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px;">
            <div class="form-group" style="flex: 1;">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="event_time">Event Time *</label>
                <input type="time" id="event_time" name="event_time" required>
            </div>
        </div>

        <div class="form-group">
            <label for="venue">Venue *</label>
            <input type="text" id="venue" name="venue" required placeholder="e.g. FTE Lab 3">
        </div>

        <div class="form-group">
            <label for="coordinator">Faculty Coordinator</label>
            <input type="text" id="coordinator" name="coordinator" placeholder="e.g. Dr. Amit Patel">
        </div>

        <div class="form-group">
            <label for="max_seats">Maximum Seats Available</label>
            <input type="number" id="max_seats" name="max_seats" value="100">
        </div>

        <div class="form-group">
            <label for="poster">Upload Event Poster (JPG, PNG, GIF - Max 2MB)</label>
            <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.gif">
        </div>

        <div class="form-group">
            <label for="description">Event Description</label>
            <textarea id="description" name="description" rows="3" placeholder="Brief event summary..."></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-success" style="flex: 1;">Publish Event</button>
            <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
