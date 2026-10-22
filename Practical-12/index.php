<?php
// Practical 12: Event Management CRUD with Poster Uploads
require_once 'db.php';

$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);

$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 12 - Event Management CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="header-row">
        <div>
            <h1>Event Management & Poster Uploads</h1>
            <p style="font-size: 13px; color: #64748b;">Practical 12: Event CRUD with File Upload, Size & MIME Type Validation</p>
        </div>
        <div>
            <a href="add_event.php" class="btn btn-success">+ Create New Event</a>
        </div>
    </div>

    <!-- Alert Notices -->
    <?php if ($message === 'added'): ?>
        <div class="alert alert-success">✔ Event created and poster uploaded successfully!</div>
    <?php elseif ($message === 'updated'): ?>
        <div class="alert alert-success">✔ Event record updated successfully!</div>
    <?php elseif ($message === 'deleted'): ?>
        <div class="alert alert-success">✔ Event record and poster file removed successfully!</div>
    <?php elseif ($message === 'error'): ?>
        <div class="alert alert-error">✖ An error occurred during database processing.</div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Event Title</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Coordinator</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($event = mysqli_fetch_assoc($result)): ?>
                        <?php 
                            $posterPath = "uploads/" . htmlspecialchars($event['poster_image']);
                            $displayPoster = (!empty($event['poster_image']) && file_exists($posterPath)) ? $posterPath : "uploads/placeholder.png";
                        ?>
                        <tr>
                            <td>
                                <?php if (file_exists($posterPath) && !empty($event['poster_image'])): ?>
                                    <img src="<?php echo $posterPath; ?>" class="poster-thumb" alt="Poster">
                                <?php else: ?>
                                    <span style="font-size: 11px; color: #64748b; background: #e2e8f0; padding: 4px 6px; border-radius: 4px;">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                            <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo htmlspecialchars($event['category']); ?></span></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?><br><small style="color: #64748b;"><?php echo htmlspecialchars($event['event_time']); ?></small></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td><?php echo htmlspecialchars($event['coordinator']); ?></td>
                            <td><?php echo htmlspecialchars($event['max_seats']); ?></td>
                            <td><span style="color: green; font-weight: bold; font-size: 12px;"><?php echo htmlspecialchars($event['status']); ?></span></td>
                            <td style="text-align: center; white-space: nowrap;">
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete event: <?php echo htmlspecialchars(addslashes($event['title'])); ?>?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #64748b; padding: 30px;">
                            No campus events found in database. Click "+ Create New Event" to post one!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p style="font-size: 13px; color: #64748b; margin-top: 15px;">
        Total Campus Events: <strong><?php echo $result ? mysqli_num_rows($result) : 0; ?></strong>
    </p>
</div>

</body>
</html>
