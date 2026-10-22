<?php
// Practical 11: Student Management CRUD with Search & Filter
require_once 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dept   = isset($_GET['department']) ? trim($_GET['department']) : '';

// Build dynamic SQL query
$query = "SELECT * FROM students WHERE 1=1";

if (!empty($search)) {
    $cleanSearch = mysqli_real_escape_string($conn, $search);
    $query .= " AND (full_name LIKE '%$cleanSearch%' OR roll_number LIKE '%$cleanSearch%' OR email LIKE '%$cleanSearch%')";
}

if (!empty($dept) && $dept !== 'all') {
    $cleanDept = mysqli_real_escape_string($conn, $dept);
    $query .= " AND department = '$cleanDept'";
}

$query .= " ORDER BY roll_number ASC";
$result = mysqli_query($conn, $query);

$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 11 - Student Management CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="header-row">
        <div>
            <h1>Student Management Module</h1>
            <p style="font-size: 13px; color: #64748b;">Practical 11: PHP-MySQLi CRUD with Search, Filter & Confirm Dialogs</p>
        </div>
        <div>
            <a href="add_student.php" class="btn btn-success">+ Add New Student</a>
        </div>
    </div>

    <!-- Feedback messages -->
    <?php if ($message === 'added'): ?>
        <div class="alert alert-success">✔ Student record created successfully!</div>
    <?php elseif ($message === 'updated'): ?>
        <div class="alert alert-success">✔ Student record updated successfully!</div>
    <?php elseif ($message === 'deleted'): ?>
        <div class="alert alert-success">✔ Student record deleted successfully!</div>
    <?php elseif ($message === 'error'): ?>
        <div class="alert alert-error">✖ An error occurred during database processing.</div>
    <?php endif; ?>

    <!-- Search & Filter Form -->
    <form method="GET" action="index.php" class="filter-card">
        <div style="flex: 2; min-width: 220px;">
            <input type="text" name="search" placeholder="Search by name, roll no, or email..." value="<?php echo htmlspecialchars($search); ?>" style="width: 100%;">
        </div>
        <div style="flex: 1; min-width: 160px;">
            <select name="department" style="width: 100%;">
                <option value="all">All Departments</option>
                <option value="IT" <?php if ($dept === 'IT') echo 'selected'; ?>>Information Technology (IT)</option>
                <option value="CSE" <?php if ($dept === 'CSE') echo 'selected'; ?>>Computer Science (CSE)</option>
                <option value="CE" <?php if ($dept === 'CE') echo 'selected'; ?>>Computer Engineering (CE)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search / Filter</button>
        <?php if (!empty($search) || (!empty($dept) && $dept !== 'all')): ?>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <!-- Students Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>CGPA</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['roll_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><span style="background: #e0f2fe; color:#0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight:bold;"><?php echo htmlspecialchars($row['department']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['year_of_study']); ?> Year</td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                            <td><?php echo htmlspecialchars($row['cgpa']); ?></td>
                            <td style="text-align: center; white-space: nowrap;">
                                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete student: <?php echo htmlspecialchars(addslashes($row['full_name'])); ?>?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 30px;">
                            No student records found matching the query.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p style="font-size: 13px; color: #64748b; margin-top: 15px;">
        Total Students Shown: <strong><?php echo $result ? mysqli_num_rows($result) : 0; ?></strong>
    </p>
</div>

</body>
</html>
