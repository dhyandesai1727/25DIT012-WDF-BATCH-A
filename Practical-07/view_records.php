<?php
// Practical 7 Extension: View Stored CSV and JSON Records

$csvFile = __DIR__ . "/submissions.csv";
$jsonFile = __DIR__ . "/submissions.json";

// Read CSV rows
$csvRows = [];
if (file_exists($csvFile)) {
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $csvRows[] = $data;
        }
        fclose($handle);
    }
}

// Read JSON records
$jsonRecords = [];
if (file_exists($jsonFile)) {
    $raw = file_get_contents($jsonFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $jsonRecords = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stored Records - Practical 7</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 950px; }
        .section-title { color: #1e3a8a; margin-top: 30px; font-size: 18px; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Stored Student Records (CSV & JSON)</h1>
    <p class="subtitle">Practical 7: Demonstrating server-side file persistence</p>

    <div style="margin-bottom: 20px;">
        <a href="index.php" class="btn-secondary">&larr; Back to Registration Form</a>
    </div>

    <!-- CSV Table -->
    <h2 class="section-title">1. Records from `submissions.csv` (<?php echo count($csvRows) > 1 ? count($csvRows) - 1 : 0; ?> records)</h2>
    <?php if (!empty($csvRows)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($csvRows[0] as $header): ?>
                        <th><?php echo htmlspecialchars($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i < count($csvRows); $i++): ?>
                    <tr>
                        <?php foreach ($csvRows[$i] as $col): ?>
                            <td><?php echo htmlspecialchars($col); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="padding: 15px; color: #64748b;">No CSV records found.</p>
    <?php endif; ?>

    <!-- JSON Table -->
    <h2 class="section-title">2. Records from `submissions.json` (<?php echo count($jsonRecords); ?> records)</h2>
    <?php if (!empty($jsonRecords)): ?>
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Mobile</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jsonRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['timestamp'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['email'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['mobile'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['course'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['year'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($record['gender'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="padding: 15px; color: #64748b;">No JSON records found.</p>
    <?php endif; ?>
</div>

</body>
</html>
