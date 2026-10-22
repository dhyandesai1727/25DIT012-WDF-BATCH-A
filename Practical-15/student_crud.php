<?php
// Practical 15: Student CRUD Management
session_start();
require_once 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dept   = isset($_GET['department']) ? trim($_GET['department']) : '';
$msg    = $_GET['msg'] ?? '';

// Handle Delete Request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delId = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM students WHERE id = $delId");
    logActivity($conn, $_SESSION['user_id'] ?? 1, $_SESSION['username'] ?? 'admin', 'DELETE_STUDENT', "Deleted student ID $delId");
    header("Location: student_crud.php?msg=deleted");
    exit;
}

// Handle Add Student
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_student'])) {
    $roll   = mysqli_real_escape_string($conn, trim($_POST['roll_number']));
    $name   = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email  = mysqli_real_escape_string($conn, trim($_POST['email']));
    $mobile = mysqli_real_escape_string($conn, trim($_POST['mobile']));
    $dpt    = mysqli_real_escape_string($conn, trim($_POST['department']));
    $yr     = mysqli_real_escape_string($conn, trim($_POST['year_of_study']));
    $gen    = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $cgpa   = floatval($_POST['cgpa']);

    $ins = "INSERT INTO students (roll_number, full_name, email, mobile, department, year_of_study, gender, cgpa)
            VALUES ('$roll', '$name', '$email', '$mobile', '$dpt', '$yr', '$gen', $cgpa)";
    if (mysqli_query($conn, $ins)) {
        logActivity($conn, $_SESSION['user_id'] ?? 1, $_SESSION['username'] ?? 'admin', 'ADD_STUDENT', "Created student $roll - $name");
        header("Location: student_crud.php?msg=added");
        exit;
    }
}

// Build Search Query
$sql = "SELECT * FROM students WHERE 1=1";
if (!empty($search)) {
    $cleanS = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (full_name LIKE '%$cleanS%' OR roll_number LIKE '%$cleanS%' OR email LIKE '%$cleanS%')";
}
if (!empty($dept) && $dept !== 'all') {
    $cleanD = mysqli_real_escape_string($conn, $dept);
    $sql .= " AND department = '$cleanD'";
}
$sql .= " ORDER BY roll_number ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Practical 15</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav-wrap">
        <a href="index.php" class="brand">StudentHub FTE</a>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="student_crud.php" class="active">Students</a></li>
            <li><a href="event_crud.php">Events</a></li>
            <li><a href="logout.php" class="btn-nav-logout">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #1e3a8a;">Student Directory & Management</h1>
        <button class="btn btn-success" onclick="document.getElementById('addModal').style.display='block';">+ Add New Student</button>
    </div>

    <?php if ($msg === 'added'): ?><div class="alert alert-success">✔ Student record created!</div><?php endif; ?>
    <?php if ($msg === 'deleted'): ?><div class="alert alert-success">✔ Student record deleted!</div><?php endif; ?>

    <!-- Search / Filter -->
    <form method="GET" action="student_crud.php" style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #cbd5e1; display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search roll, name, or email..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 2; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
        <select name="department" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
            <option value="all">All Departments</option>
            <option value="IT" <?php if ($dept === 'IT') echo 'selected'; ?>>IT</option>
            <option value="CSE" <?php if ($dept === 'CSE') echo 'selected'; ?>>CSE</option>
            <option value="CE" <?php if ($dept === 'CE') echo 'selected'; ?>>CE</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if (!empty($search) || !empty($dept)): ?><a href="student_crud.php" class="btn btn-secondary">Clear</a><?php endif; ?>
    </form>

    <div class="card" style="padding: 0; overflow-x: auto;">
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
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($s['roll_number']); ?></strong></td>
                        <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                        <td><span class="badge" style="background:#e0f2fe; color:#0369a1;"><?php echo htmlspecialchars($s['department']); ?></span></td>
                        <td><?php echo htmlspecialchars($s['year_of_study']); ?> Year</td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><?php echo htmlspecialchars($s['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($s['cgpa']); ?></td>
                        <td style="text-align: center;">
                            <a href="student_crud.php?action=delete&id=<?php echo $s['id']; ?>" class="btn btn-danger" style="padding: 3px 8px; font-size: 11px;" onclick="return confirm('Delete student: <?php echo htmlspecialchars(addslashes($s['full_name'])); ?>?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Add Student Modal -->
<div id="addModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100;">
    <div style="background:white; max-width:500px; margin:50px auto; padding:25px; border-radius:8px; position:relative;">
        <button onclick="document.getElementById('addModal').style.display='none';" style="position:absolute; right:15px; top:15px; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        <h3 style="color:#1e3a8a; margin-bottom:15px;">Add Student Record</h3>
        <form action="student_crud.php" method="POST">
            <input type="hidden" name="add_student" value="1">
            <div class="form-group"><label>Roll Number *</label><input type="text" name="roll_number" required placeholder="25DIT120"></div>
            <div class="form-group"><label>Full Name *</label><input type="text" name="full_name" required placeholder="John Doe"></div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" required placeholder="john@charusat.edu.in"></div>
            <div class="form-group"><label>Mobile *</label><input type="tel" name="mobile" required maxlength="10"></div>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Department</label>
                    <select name="department"><option value="IT">IT</option><option value="CSE">CSE</option><option value="CE">CE</option></select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Year</label>
                    <select name="year_of_study"><option value="1st">1st</option><option value="2nd">2nd</option><option value="3rd">3rd</option><option value="4th">4th</option></select>
                </div>
            </div>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Gender</label>
                    <select name="gender"><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>CGPA</label>
                    <input type="number" step="0.01" min="0" max="10" name="cgpa" value="8.00">
                </div>
            </div>
            <button type="submit" class="btn btn-success" style="width:100%; padding:10px;">Save Student Record</button>
        </form>
    </div>
</div>

</body>
</html>
