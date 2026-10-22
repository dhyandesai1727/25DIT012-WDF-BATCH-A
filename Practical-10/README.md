# Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout & Remember-Me

## 1. Overview
This practical implements complete session-based authentication in PHP, including BCRYPT password verification (`password_verify`), session fixation countermeasure (`session_regenerate_id(true)`), 15-minute inactivity timeout, role-based access control (Student vs Admin), and persistent Remember-Me tokens.

---

## 2. Answers to Key Interpretation Questions

### Q1: Are sessions started and destroyed securely?
- **Starting & Securing:** `session_start()` initiates the session. Upon credential verification, `session_regenerate_id(true)` replaces the existing session identifier with a new one to prevent Session Fixation exploits.
- **Destroying:** `logout.php` empties the `$_SESSION` array, invalidates the `PHPSESSID` cookie by backdating its expiration, removes any Remember-Me cookie, and executes `session_destroy()`.

### Q2: Is `password_verify()` used correctly?
Yes. The stored hash from MySQL is compared against user input:
```php
if (password_verify($password, $user['password_hash'])) { ... }
```
`password_verify()` performs a timing-attack safe comparison against the embedded salt and cost factor.

### Q3: Is unauthorized dashboard access blocked?
Yes. `auth_check.php` validates that `$_SESSION['user_id']` is present. If missing, requests are immediately redirected to `login.php?msg=unauthorized`. Furthermore, `requireRole('admin')` restricts admin routes so student accounts cannot access admin privileges.

### Q4: Is session timeout implemented?
Yes. The timestamp `$_SESSION['last_activity']` is compared against current `time()`. If `(time() - $_SESSION['last_activity']) > 900` (15 minutes), the session is purged and the user is redirected to `login.php?msg=timeout`.

### Q5: Are admin and student users redirected to appropriate dashboards?
Yes. Based on `$_SESSION['role']`:
- Admin accounts redirect to `dashboard_admin.php`.
- Student accounts redirect to `dashboard_student.php`.
