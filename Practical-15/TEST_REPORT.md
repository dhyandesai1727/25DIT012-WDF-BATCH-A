# StudentHub Full-Stack Web Application - Comprehensive Test Report

**Subject:** Web Development Frameworks (ITUE203) - Semester 3  
**Institution:** CHARUSAT - Faculty of Technology & Engineering (FTE)  
**Academic Year:** 2026-27  

---

## 1. Functional Test Execution Summary

| Test ID | Module Tested | Test Steps | Expected Output | Actual Output | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-01** | **Semantic HTML & Navigation** | Open all interior pages (Home, About, Events, FAQs, Contact). | Header, landmarks, and footer link consistently with zero dead links. | All 11 pages link smoothly across relative paths. | **PASSED** |
| **TC-02** | **Responsive Design** | Scale viewport across mobile (<640px), tablet (768px), and desktop (1140px). | Layout transitions from 1-column stack to 2-column and 3-column CSS Grid. | No horizontal page scrolling; cards align responsively. | **PASSED** |
| **TC-03** | **DOM & LocalStorage** | Toggle dark mode button and reload browser. | Theme changes color variables and remains dark across refreshes. | Theme key is preserved in `localStorage`. | **PASSED** |
| **TC-04** | **Frontend Validation** | Submit registration form with invalid email (`user@com`) and 5-digit mobile. | Form submission is prevented; red error labels display near inputs. | Inputs outline in red and error messages display. | **PASSED** |
| **TC-05** | **Password Strength Meter** | Type weak (`abc`), medium (`Abc12`), and strong (`Abc@1234`) passwords. | Progress bar changes color (Red &rarr; Orange &rarr; Green). | Visual feedback updates synchronously on keyup. | **PASSED** |
| **TC-06** | **Fetch API JSON Explorer** | Select "Campus Events", "Students", "FAQs" tabs; search query "Hackathon". | Data cards filter immediately with pagination controls. | Array filters and pagination slice work correctly. | **PASSED** |
| **TC-07** | **PHP File Storage (CSV & JSON)** | Submit form in Practical 7. | New row appends to `submissions.csv` and `submissions.json`. | Verified in `view_records.php`. | **PASSED** |
| **TC-08** | **Database Connectivity** | Run `test_db.php` in browser. | Connects to `studenthub` database and counts records in tables. | Displays green status badge and table metrics. | **PASSED** |
| **TC-09** | **Password Hashing** | Register new user; inspect MySQL `users` table in phpMyAdmin. | Password is stored as a 60-character BCRYPT hash (`$2y$10$...`). | Plaintext password never stored. | **PASSED** |
| **TC-10** | **Duplicate Email Check** | Register again with an already existing email address. | Query detects conflict and rejects registration with error banner. | Duplicate insert prevented. | **PASSED** |
| **TC-11** | **Role-Based Redirection** | Log in with `admin` credentials; then log in with `rahul12` student account. | Admin redirects to `admin_dashboard.php`; student redirects to `student_dashboard.php`. | Role routing succeeds. | **PASSED** |
| **TC-12** | **Session Inactivity Timeout** | Leave session idle for > 15 minutes (900 seconds). | Next click redirects to `login.php?msg=timeout`. | Inactivity logout succeeds. | **PASSED** |
| **TC-13** | **Student CRUD** | Add student, search by name, filter by department, delete with confirm box. | Database table reflects modifications; alert messages display. | CRUD operations succeed. | **PASSED** |
| **TC-14** | **Poster File Upload** | Upload 1.5MB PNG flyer; try uploading 5MB file. | 1.5MB file uploads to `uploads/`; 5MB file is rejected with size error. | File size and extension restrictions verified. | **PASSED** |
| **TC-15** | **Asynchronous AJAX CRUD** | Create and delete event on `ajax_events.html`. | Event row adds/removes dynamically without page reload. | DOM updates live via `fetch()`. | **PASSED** |
| **TC-16** | **Audit Trail Logging** | Execute admin actions (login, student CRUD, event delete). | Rows are inserted into `audit_logs` with IP, timestamp, and details. | Verified in `audit_logs.php`. | **PASSED** |

---

## 2. Security & Accessibility Audit

- **SQL Injection Prevention:** Input sanitization using `mysqli_real_escape_string()` and type casting (`intval()`, `floatval()`).
- **Cross-Site Scripting (XSS) Prevention:** Output escaping using `htmlspecialchars()` across all dynamic variables.
- **Session Fixation Prevention:** Calling `session_regenerate_id(true)` immediately following authentication.
- **Accessibility:** Semantic HTML5 tags (`<header>`, `<nav>`, `<main>`, `<footer>`), form `<label for="...">` associations, high contrast ratios ($>4.5:1$), and skip links.
