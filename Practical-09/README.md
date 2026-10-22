# Practical 9: Secure User Registration with Database Insert, Duplicate Email Check & Password Hashing

## 1. Overview
This practical implements a secure registration pipeline using PHP and MySQLi. It performs client and server validation, protects the database from duplicate registrations (duplicate username and email checking), hashes passwords using modern algorithms, and provides clear user feedback.

---

## 2. Answers to Key Interpretation Questions

### Q1: Is `password_hash()` used before storing passwords?
Yes. Passwords are never stored in plain text. We utilize:
```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```
`PASSWORD_DEFAULT` currently invokes the robust **BCRYPT** hashing algorithm with a random cryptographic salt, generating a 60-character hash (e.g. `$2y$10$...`) resistant to rainbow table and dictionary attacks.

### Q2: Is duplicate email/username checked?
Yes. Prior to inserting the user, a `SELECT` query runs:
```php
SELECT id, email, username FROM users WHERE email = '$cleanEmail' OR username = '$cleanUser' LIMIT 1
```
If an existing record matches either field, a user-friendly error message is displayed and execution halts before running any `INSERT` query.

### Q3: Are frontend and backend validations consistent?
Yes. Both client-side JavaScript (`validateFrontend()`) and PHP backend check identical rules:
- Name format: letters and spaces (min 3 chars).
- Email: standard RFC format.
- Mobile: 10 digits starting with 6-9.
- Password: minimum 6 characters and matching confirmation.
This two-tier strategy guarantees immediate feedback for the user while preventing malicious bypass attempts.
