<?php
// Practical 8: Database Connection Test Page

$host     = "localhost";
$username = "root";
$password = "";
$database = "studenthub";

$conn = @mysqli_connect($host, $username, $password, $database);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 - Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; padding: 30px 15px; color: #1e293b; }
        .box { max-width: 750px; margin: auto; background: white; padding: 25px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        h1 { color: #1e3a8a; font-size: 20px; margin-bottom: 8px; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 13px; margin: 15px 0; }
        .success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .failed { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #cbd5e1; }
        th, td { padding: 8px 12px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; color: #1e3a8a; }
        code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>

<div class="box">
    <h1>Practical 8: MySQL Schema & Connectivity Verification</h1>
    <p>Tests PHP MySQLi connectivity to the <code>studenthub</code> database in XAMPP.</p>

    <?php if ($conn): ?>
        <div class="status-badge success">✔ Connected Successfully to MySQL Database (studenthub)</div>
        <p><strong>Server Info:</strong> <?php echo mysqli_get_server_info($conn); ?></p>
        <p><strong>Host:</strong> <?php echo mysqli_get_host_info($conn); ?></p>

        <h3 style="margin-top: 20px; color: #1e3a8a;">Table Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Table Name</th>
                    <th>Row Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tables = ['students', 'events', 'registrations'];
                foreach ($tables as $tbl) {
                    $res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `$tbl`");
                    $cnt = $res ? mysqli_fetch_assoc($res)['cnt'] : "Table not found";
                    echo "<tr><td><code>$tbl</code></td><td><strong>$cnt</strong> records</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php mysqli_close($conn); ?>
    <?php else: ?>
        <div class="status-badge failed">✖ Connection Failed</div>
        <p>Could not connect to MySQL server. Error:</p>
        <pre style="background: #f1f5f9; padding: 10px; border-radius: 4px;"><?php echo mysqli_connect_error(); ?></pre>
        <p style="font-size: 13px; color: #64748b; margin-top: 10px;">
            <strong>Setup Checklist:</strong><br>
            1. Start Apache and MySQL from XAMPP Control Panel.<br>
            2. Open phpMyAdmin at <code>http://localhost/phpmyadmin/</code>.<br>
            3. Import the schema file <code>studenthub.sql</code> provided in this folder.
        </p>
    <?php endif; ?>

    <p style="margin-top: 25px;">
        <a href="er_diagram.html" style="color: #2563eb; font-weight: bold;">View Visual Entity-Relationship (ER) Diagram &rarr;</a>
    </p>
</div>

</body>
</html>
