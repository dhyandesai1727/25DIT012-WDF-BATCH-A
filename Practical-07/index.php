<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMsg = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
$old = isset($_SESSION['old_inputs']) ? $_SESSION['old_inputs'] : [];

// Clear flash session messages
unset($_SESSION['errors']);
unset($_SESSION['success_msg']);
unset($_SESSION['old_inputs']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 7 - PHP Form Processing with CSV/JSON</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>StudentHub Form Processor (PHP)</h1>
    <p class="subtitle">Practical 7: Server-Side Validation, Sanitization, and File-Based Storage</p>

    <!-- Success Message -->
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success">
            <strong>Success:</strong> <?php echo htmlspecialchars($successMsg); ?>
        </div>
    <?php endif; ?>

    <!-- Error Summary -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please resolve the following server-side errors:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="process.php" method="POST">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="<?php echo isset($old['name']) ? htmlspecialchars($old['name']) : ''; ?>" required placeholder="e.g. Rahul Patel">
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>" required placeholder="e.g. rahul@charusat.edu.in">
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number (10 digits) *</label>
            <input type="tel" id="mobile" name="mobile" value="<?php echo isset($old['mobile']) ? htmlspecialchars($old['mobile']) : ''; ?>" required placeholder="e.g. 9876543210">
        </div>

        <div class="form-group">
            <label for="course">Academic Course *</label>
            <select id="course" name="course" required>
                <option value="">-- Choose Branch --</option>
                <option value="IT" <?php echo (isset($old['course']) && $old['course'] === 'IT') ? 'selected' : ''; ?>>Information Technology</option>
                <option value="CSE" <?php echo (isset($old['course']) && $old['course'] === 'CSE') ? 'selected' : ''; ?>>Computer Science & Engg</option>
                <option value="CE" <?php echo (isset($old['course']) && $old['course'] === 'CE') ? 'selected' : ''; ?>>Computer Engineering</option>
            </select>
        </div>

        <div class="form-group">
            <label for="year">Year of Study *</label>
            <select id="year" name="year" required>
                <option value="">-- Choose Year --</option>
                <option value="1st" <?php echo (isset($old['year']) && $old['year'] === '1st') ? 'selected' : ''; ?>>1st Year</option>
                <option value="2nd" <?php echo (isset($old['year']) && $old['year'] === '2nd') ? 'selected' : ''; ?>>2nd Year</option>
                <option value="3rd" <?php echo (isset($old['year']) && $old['year'] === '3rd') ? 'selected' : ''; ?>>3rd Year</option>
                <option value="4th" <?php echo (isset($old['year']) && $old['year'] === '4th') ? 'selected' : ''; ?>>4th Year</option>
            </select>
        </div>

        <div class="form-group">
            <label>Gender *</label>
            <div style="display:flex; gap: 15px; margin-top: 5px;">
                <label style="font-weight:normal;"><input type="radio" name="gender" value="Male" <?php echo (isset($old['gender']) && $old['gender'] === 'Male') ? 'checked' : ''; ?> required> Male</label>
                <label style="font-weight:normal;"><input type="radio" name="gender" value="Female" <?php echo (isset($old['gender']) && $old['gender'] === 'Female') ? 'checked' : ''; ?>> Female</label>
                <label style="font-weight:normal;"><input type="radio" name="gender" value="Other" <?php echo (isset($old['gender']) && $old['gender'] === 'Other') ? 'checked' : ''; ?>> Other</label>
            </div>
        </div>

        <button type="submit" class="btn">Submit & Save to CSV/JSON</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="view_records.php" class="btn-secondary">View Stored CSV & JSON Records &rarr;</a>
    </div>
</div>

</body>
</html>
