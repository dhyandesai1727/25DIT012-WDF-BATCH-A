<?php
// Practical 11: Add Student Record
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roll       = trim(htmlspecialchars($_POST['roll_number']));
    $fullName   = trim(htmlspecialchars($_POST['full_name']));
    $email      = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $mobile     = trim(htmlspecialchars($_POST['mobile']));
    $department = trim(htmlspecialchars($_POST['department']));
    $year       = trim(htmlspecialchars($_POST['year_of_study']));
    $gender     = trim(htmlspecialchars($_POST['gender']));
    $cgpa       = floatval($_POST['cgpa']);

    if (empty($roll) || empty($fullName) || empty($email) || empty($mobile)) {
        $error = "Please fill in all required fields.";
    } else {
        $cleanRoll = mysqli_real_escape_string($conn, $roll);
        $cleanName = mysqli_real_escape_string($conn, $fullName);
        $cleanEmail= mysqli_real_escape_string($conn, $email);
        $cleanMob  = mysqli_real_escape_string($conn, $mobile);
        $cleanDept = mysqli_real_escape_string($conn, $department);
        $cleanYear = mysqli_real_escape_string($conn, $year);
        $cleanGen  = mysqli_real_escape_string($conn, $gender);

        $sql = "INSERT INTO students (roll_number, full_name, email, mobile, department, year_of_study, gender, cgpa)
                VALUES ('$cleanRoll', '$cleanName', '$cleanEmail', '$cleanMob', '$cleanDept', '$cleanYear', '$cleanGen', $cgpa)";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=added");
            exit;
        } else {
            $error = "Error adding student: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; margin-bottom: 6px;">Add New Student Record</h2>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Enter student details to insert into MySQL database</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="add_student.php" method="POST">
        <div class="form-group">
            <label for="roll_number">Roll Number (e.g. 25DIT110) *</label>
            <input type="text" id="roll_number" name="roll_number" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>

        <div class="form-group">
            <label for="email">University Email *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="tel" id="mobile" name="mobile" required maxlength="10">
        </div>

        <div class="form-group">
            <label for="department">Department *</label>
            <select id="department" name="department" required>
                <option value="IT">Information Technology (IT)</option>
                <option value="CSE">Computer Science (CSE)</option>
                <option value="CE">Computer Engineering (CE)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="year_of_study">Year of Study *</label>
            <select id="year_of_study" name="year_of_study" required>
                <option value="1st">1st Year</option>
                <option value="2nd">2nd Year</option>
                <option value="3rd">3rd Year</option>
                <option value="4th">4th Year</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="cgpa">Current CGPA (0.00 - 10.00)</label>
            <input type="number" step="0.01" min="0" max="10" id="cgpa" name="cgpa" value="8.00">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-success" style="flex: 1;">Save Record</button>
            <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
