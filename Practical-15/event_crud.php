<?php
// Practical 15: Event CRUD Management with Poster Upload
session_start();
require_once 'db.php';

$msg = $_GET['msg'] ?? '';

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delId = intval($_GET['id']);
    // unlink poster if exists
    $posterRes = mysqli_query($conn, "SELECT poster_image FROM events WHERE id = $delId");
    if ($posterRes && $p = mysqli_fetch_assoc($posterRes)) {
        if (!empty($p['poster_image']) && file_exists(__DIR__ . "/uploads/" . $p['poster_image'])) {
            @unlink(__DIR__ . "/uploads/" . $p['poster_image']);
        }
    }
    mysqli_query($conn, "DELETE FROM events WHERE id = $delId");
    logActivity($conn, $_SESSION['user_id'] ?? 1, $_SESSION['username'] ?? 'admin', 'DELETE_EVENT', "Deleted event ID $delId");
    header("Location: event_crud.php?msg=deleted");
    exit;
}

// Handle Add Event
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_event'])) {
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $cat         = mysqli_real_escape_string($conn, trim($_POST['category']));
    $date        = mysqli_real_escape_string($conn, trim($_POST['event_date']));
    $time        = mysqli_real_escape_string($conn, trim($_POST['event_time']));
    $venue       = mysqli_real_escape_string($conn, trim($_POST['venue']));
    $coord       = mysqli_real_escape_string($conn, trim($_POST['coordinator']));
    $seats       = intval($_POST['max_seats']);
    $desc        = mysqli_real_escape_string($conn, trim($_POST['description']));
    $posterImage = '';

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['poster']['size'] <= 2 * 1024 * 1024) {
            $fName = "event_" . time() . "_" . rand(100, 999) . "." . $ext;
            if (!is_dir(__DIR__ . "/uploads/")) mkdir(__DIR__ . "/uploads/", 0755, true);
            if (move_uploaded_file($_FILES['poster']['tmp_name'], __DIR__ . "/uploads/" . $fName)) {
                $posterImage = $fName;
            }
        }
    }

    $ins = "INSERT INTO events (title, category, event_date, event_time, venue, coordinator, max_seats, description, poster_image)
            VALUES ('$title', '$cat', '$date', '$time', '$venue', '$coord', $seats, '$desc', '$posterImage')";
    if (mysqli_query($conn, $ins)) {
        logActivity($conn, $_SESSION['user_id'] ?? 1, $_SESSION['username'] ?? 'admin', 'ADD_EVENT', "Created event: $title");
        header("Location: event_crud.php?msg=added");
        exit;
    }
}

$eventsResult = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav-wrap">
        <a href="index.php" class="brand">StudentHub FTE</a>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="student_crud.php">Students</a></li>
            <li><a href="event_crud.php" class="active">Events</a></li>
            <li><a href="logout.php" class="btn-nav-logout">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #1e3a8a;">Campus Events Management</h1>
        <button class="btn btn-success" onclick="document.getElementById('addEventModal').style.display='block';">+ Create Event</button>
    </div>

    <?php if ($msg === 'added'): ?><div class="alert alert-success">✔ Event published successfully!</div><?php endif; ?>
    <?php if ($msg === 'deleted'): ?><div class="alert alert-success">✔ Event deleted successfully!</div><?php endif; ?>

    <div class="card" style="padding: 0; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Coordinator</th>
                    <th>Seats</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($e = mysqli_fetch_assoc($eventsResult)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($e['poster_image']) && file_exists(__DIR__ . "/uploads/" . $e['poster_image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($e['poster_image']); ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;" alt="Poster">
                            <?php else: ?>
                                <span style="font-size: 11px; color: #64748b;">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($e['title']); ?></strong></td>
                        <td><span class="badge" style="background:#e0f2fe; color:#0369a1;"><?php echo htmlspecialchars($e['category']); ?></span></td>
                        <td><?php echo htmlspecialchars($e['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($e['venue']); ?></td>
                        <td><?php echo htmlspecialchars($e['coordinator']); ?></td>
                        <td><?php echo htmlspecialchars($e['max_seats']); ?></td>
                        <td>
                            <a href="event_crud.php?action=delete&id=<?php echo $e['id']; ?>" class="btn btn-danger" style="padding: 3px 8px; font-size: 11px;" onclick="return confirm('Delete event: <?php echo htmlspecialchars(addslashes($e['title'])); ?>?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Add Event Modal -->
<div id="addEventModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100;">
    <div style="background:white; max-width:550px; margin:40px auto; padding:25px; border-radius:8px; position:relative;">
        <button onclick="document.getElementById('addEventModal').style.display='none';" style="position:absolute; right:15px; top:15px; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        <h3 style="color:#1e3a8a; margin-bottom:15px;">Publish Campus Event</h3>
        <form action="event_crud.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_event" value="1">
            <div class="form-group"><label>Event Title *</label><input type="text" name="title" required placeholder="Robotics Derby 2026"></div>
            <div class="form-group">
                <label>Category</label>
                <select name="category"><option value="Technical">Technical</option><option value="Cultural">Cultural</option><option value="Sports">Sports</option><option value="Workshop">Workshop</option></select>
            </div>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;"><label>Date *</label><input type="date" name="event_date" required></div>
                <div class="form-group" style="flex:1;"><label>Time *</label><input type="time" name="event_time" value="10:00:00" required></div>
            </div>
            <div class="form-group"><label>Venue *</label><input type="text" name="venue" required placeholder="Lab 3 / Auditorium"></div>
            <div class="form-group"><label>Coordinator</label><input type="text" name="coordinator" placeholder="Dr. Jignesh Solanki"></div>
            <div class="form-group"><label>Max Seats</label><input type="number" name="max_seats" value="100"></div>
            <div class="form-group"><label>Event Poster Flyer (JPG, PNG, GIF - Max 2MB)</label><input type="file" name="poster" accept=".jpg,.jpeg,.png,.gif"></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success" style="width:100%; padding:10px;">Publish Event</button>
        </form>
    </div>
</div>

</body>
</html>
