<?php
// Practical 7: PHP Form Processing with Server-Side Validation & CSV/JSON Storage

session_start();

$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Sanitize Inputs
    $name    = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $mobile  = isset($_POST['mobile']) ? trim(htmlspecialchars($_POST['mobile'])) : '';
    $course  = isset($_POST['course']) ? trim(htmlspecialchars($_POST['course'])) : '';
    $year    = isset($_POST['year']) ? trim(htmlspecialchars($_POST['year'])) : '';
    $gender  = isset($_POST['gender']) ? trim(htmlspecialchars($_POST['gender'])) : '';

    // 2. Server-Side Validations
    if (empty($name)) {
        $errors[] = "Full Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
        $errors[] = "Name must contain only letters and spaces (minimum 3 characters).";
    }

    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($mobile)) {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = "Mobile number must be a valid 10-digit number starting with 6, 7, 8, or 9.";
    }

    if (empty($course)) {
        $errors[] = "Please select a course/branch.";
    }

    if (empty($year)) {
        $errors[] = "Please select your year of study.";
    }

    if (empty($gender)) {
        $errors[] = "Please select your gender.";
    }

    // 3. If Valid: Store into CSV & JSON
    if (empty($errors)) {
        $timestamp = date("Y-m-d H:i:s");

        // --- Append to CSV File ---
        $csvFile = __DIR__ . "/submissions.csv";
        $csvRow = [$timestamp, $name, $email, $mobile, $course, $year, $gender];

        $fp = fopen($csvFile, "a");
        if ($fp) {
            fputcsv($fp, $csvRow);
            fclose($fp);
        }

        // --- Append to JSON File ---
        $jsonFile = __DIR__ . "/submissions.json";
        $jsonData = [];

        if (file_exists($jsonFile)) {
            $rawJson = file_get_contents($jsonFile);
            $jsonData = json_decode($rawJson, true);
            if (!is_array($jsonData)) {
                $jsonData = [];
            }
        }

        $jsonData[] = [
            "timestamp" => $timestamp,
            "name"      => $name,
            "email"     => $email,
            "mobile"    => $mobile,
            "course"    => $course,
            "year"      => $year,
            "gender"    => $gender
        ];

        file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));

        $_SESSION['success_msg'] = "Registration saved successfully in both CSV and JSON storage!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_inputs'] = [
            'name'   => $name,
            'email'  => $email,
            'mobile' => $mobile,
            'course' => $course,
            'year'   => $year,
            'gender' => $gender
        ];
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
