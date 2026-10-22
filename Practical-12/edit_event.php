<?php
// Practical 12: Edit Event with Optional Poster Replacement
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$errors = [];

$selectSql = "SELECT * FROM events WHERE id = $id LIMIT 1";
$res = mysqli_query($conn, $selectSql);
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: index.php?msg=error");
    exit;
}
$event = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim(htmlspecialchars($_POST['title']));
    $category    = trim(htmlspecialchars($_POST['category']));
    $eventDate   = trim(htmlspecialchars($_POST['event_date']));
    $eventTime   = trim(htmlspecialchars($_POST['event_time']));
    $venue       = trim(htmlspecialchars($_POST['venue']));
    $coordinator = trim(htmlspecialchars($_POST['coordinator']));
    $maxSeats    = intval($_POST['max_seats']);
    $description = trim(htmlspecialchars($_POST['description']));
    $status      = trim(htmlspecialchars($_POST['status']));
    $posterName  = $event['poster_image']; // Preserve existing poster by default

    // Check if new poster uploaded
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['poster']['tmp_name'];
        $originalName  = $_FILES['poster']['name'];
        $fileSize      = $_FILES['poster']['size'];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($fileSize > 2 * 1024 * 1024) {
            $errors[] = "New poster image must be smaller than 2MB.";
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF allowed.";
        }

        if (empty($errors)) {
            $newFileName = "event_" . time() . "_" . rand(1000, 9999) . "." . $fileExtension;
            $uploadDir = __DIR__ . "/uploads/";
            $targetFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                // Delete previous file if exists
                if (!empty($event['poster_image']) && file_exists($uploadDir . $event['poster_image'])) {
                    @unlink($uploadDir . $event['poster_image']);
                }
                $posterName = $newFileName;
            } else {
                $errors[] = "Failed to upload new poster.";
            }
        }
    }

    if (empty($errors)) {
        $cleanTitle  = mysqli_real_escape_string($conn, $title);
        $cleanCat    = mysqli_real_escape_string($conn, $category);
        $cleanDate   = mysqli_real_escape_string($conn, $eventDate);
        $cleanTime   = mysqli_real_escape_string($conn, $eventTime);
        $cleanVenue  = mysqli_real_escape_string($conn, $venue);
        $cleanCoord  = mysqli_real_escape_string($conn, $coordinator);
        $cleanDesc   = mysqli_real_escape_string($conn, $description);
        $cleanStat   = mysqli_real_escape_string($conn, $status);
        $cleanPoster = mysqli_real_escape_string($conn, $posterName);

        $updateSql = "UPDATE events SET 
                        title = '$cleanTitle',
                        category = '$cleanCat',
                        event_date = '$cleanDate',
                        event_time = '$cleanTime',
                        venue = '$cleanVenue',
                        coordinator = '$cleanCoord',
                        max_seats = $maxSeats,
                        description = '$cleanDesc',
                        status = '$cleanStat',
                        poster_image = '$cleanPoster'
                      WHERE id = $id";

        if (mysqli_query($conn, $updateSql)) {
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $errors[] = "Error updating event: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Practical 12</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; margin-bottom: 6px;">Edit Campus Event</h2>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Updating Event ID #<?php echo $id; ?></p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form action="edit_event.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Event Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category *</label>
            <select id="category" name="category" required>
                <option value="Technical" <?php if ($event['category'] === 'Technical') echo 'selected'; ?>>Technical</option>
                <option value="Cultural" <?php if ($event['category'] === 'Cultural') echo 'selected'; ?>>Cultural</option>
                <option value="Sports" <?php if ($event['category'] === 'Sports') echo 'selected'; ?>>Sports</option>
                <option value="Workshop" <?php if ($event['category'] === 'Workshop') echo 'selected'; ?>>Workshop</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px;">
            <div class="form-group" style="flex: 1;">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="event_time">Event Time *</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time']); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="venue">Venue *</label>
            <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
        </div>

        <div class="form-group">
            <label for="coordinator">Faculty Coordinator</label>
            <input type="text" id="coordinator" name="coordinator" value="<?php echo htmlspecialchars($event['coordinator']); ?>">
        </div>

        <div class="form-group">
            <label for="status">Event Status</label>
            <select id="status" name="status">
                <option value="Upcoming" <?php if ($event['status'] === 'Upcoming') echo 'selected'; ?>>Upcoming</option>
                <option value="Ongoing" <?php if ($event['status'] === 'Ongoing') echo 'selected'; ?>>Ongoing</option>
                <option value="Completed" <?php if ($event['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Cancelled" <?php if ($event['status'] === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>
        </div>

        <div class="form-group">
            <label for="max_seats">Maximum Seats</label>
            <input type="number" id="max_seats" name="max_seats" value="<?php echo htmlspecialchars($event['max_seats']); ?>">
        </div>

        <div class="form-group">
            <label for="poster">Replace Poster Image (Optional)</label>
            <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.gif">
            <?php if (!empty($event['poster_image'])): ?>
                <p style="font-size: 12px; color: #64748b; margin-top: 5px;">Current Poster: <code><?php echo htmlspecialchars($event['poster_image']); ?></code></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description">Event Description</label>
            <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-warning" style="flex: 1;">Update Event</button>
            <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
