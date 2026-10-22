<?php
// Practical 11: Edit Student Record
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';

// Fetch existing record
$selectSql = "SELECT * FROM students WHERE id = $id LIMIT 1";
$res = mysqli_query($conn, $selectSql);
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: index.php?msg=error");
    exit;
}
$student = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roll       = trim(htmlspecialchars($_POST['roll_number']));
    $fullName   = trim(htmlspecialchars($_POST['full_name']));
    $email      = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $mobile     = trim(htmlspecialchars($_POST['mobile']));
    $department = trim(htmlspecialchars($_POST['department']));
    $year       = trim(htmlspecialchars($_POST['year_of_study']));
    $gender     = trim(htmlspecialchars($_POST['gender']));
    $cgpa       = floatval($_POST['cgpa']);

    $cleanRoll = mysqli_real_escape_string($conn, $roll);
    $cleanName = mysqli_real_escape_string($conn, $fullName);
    $cleanEmail= mysqli_real_escape_string($conn, $email);
    $cleanMob  = mysqli_real_escape_string($conn, $mobile);
    $cleanDept = mysqli_real_escape_string($conn, $department);
    $cleanYear = mysqli_real_escape_string($conn, $year);
    $cleanGen  = mysqli_real_escape_string($conn, $gender);

    $updateSql = "UPDATE students SET 
                    roll_number = '$cleanRoll',
                    full_name = '$cleanName',
                    email = '$cleanEmail',
                    mobile = '$cleanMob',
                    department = '$cleanDept',
                    year_of_study = '$cleanYear',
                    gender = '$cleanGen',
                    cgpa = $cgpa
                  WHERE id = $id";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: index.php?msg=updated");
        exit;
    } else {
        $error = "Error updating record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2 style="color: #1e3a8a; margin-bottom: 6px;">Edit Student Record</h2>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Updating record for ID #<?php echo $id; ?></p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="edit_student.php?id=<?php echo $id; ?>" method="POST">
        <div class="form-group">
            <label for="roll_number">Roll Number *</label>
            <input type="text" id="roll_number" name="roll_number" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">University Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student['mobile']); ?>" required maxlength="10">
        </div>

        <div class="form-group">
            <label for="department">Department *</label>
            <select id="department" name="department" required>
                <option value="IT" <?php if ($student['department'] === 'IT') echo 'selected'; ?>>Information Technology (IT)</option>
                <option value="CSE" <?php if ($student['department'] === 'CSE') echo 'selected'; ?>>Computer Science (CSE)</option>
                <option value="CE" <?php if ($student['department'] === 'CE') echo 'selected'; ?>>Computer Engineering (CE)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="year_of_study">Year of Study *</label>
            <select id="year_of_study" name="year_of_study" required>
                <option value="1st" <?php if ($student['year_of_study'] === '1st') echo 'selected'; ?>>1st Year</option>
                <option value="2nd" <?php if ($student['year_of_study'] === '2nd') echo 'selected'; ?>>2nd Year</option>
                <option value="3rd" <?php if ($student['year_of_study'] === '3rd') echo 'selected'; ?>>3rd Year</option>
                <option value="4th" <?php if ($student['year_of_study'] === '4th') echo 'selected'; ?>>4th Year</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if ($student['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($student['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($student['gender'] === 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="cgpa">Current CGPA</label>
            <input type="number" step="0.01" min="0" max="10" id="cgpa" name="cgpa" value="<?php echo htmlspecialchars($student['cgpa']); ?>">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-warning" style="flex: 1;">Update Record</button>
            <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
