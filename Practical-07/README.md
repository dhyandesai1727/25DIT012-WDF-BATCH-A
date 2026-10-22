# Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

## 1. Overview
This practical demonstrates backend form handling using procedural PHP. It receives user submissions via HTTP POST, performs rigorous server-side validation and sanitization, and appends valid records simultaneously to comma-separated (`submissions.csv`) and JSON (`submissions.json`) files.

---

## 2. Answers to Key Interpretation Questions

### Q1: Is the form submitted using POST?
Yes. The form explicitly specifies `method="POST"` to ensure user details (such as email, mobile number, and personal details) are not exposed inside URL query strings, browser history, or server access logs.

### Q2: Are inputs validated and sanitized server-side?
Yes:
- **Sanitization:** `htmlspecialchars()` converts special HTML characters (`<`, `>`, `&`, `"`) into entities to prevent Cross-Site Scripting (XSS). `filter_var(..., FILTER_SANITIZE_EMAIL)` removes illegal characters from email strings.
- **Validation:** `preg_match("/^[a-zA-Z\s]{3,50}$/", $name)` verifies alphabetical names, `filter_var(..., FILTER_VALIDATE_EMAIL)` validates email syntax, and `preg_match("/^[6-9]\d{9}$/", $mobile)` verifies 10-digit mobile numbers.

### Q3: Is file writing handled safely?
- For CSV: `fopen($csvFile, "a")` opens the file in append mode, and `fputcsv()` handles escaping, delimiters, and line endings automatically before closing with `fclose()`.
- For JSON: `file_get_contents()` reads existing records, `json_decode()` converts to an associative array, the new student object is appended, and `file_put_contents()` atomically rewrites the JSON string with `JSON_PRETTY_PRINT`.

### Q4: Are success and error responses displayed clearly?
Yes. Session flash variables (`$_SESSION['success_msg']` and `$_SESSION['errors']`) pass feedback across redirects, displaying green success banners or bulleted red error alerts.
