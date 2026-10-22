# Practical 8: MySQL Schema Design, ER Model, and Database Connectivity

## 1. Overview
This practical models a normalized relational schema for the StudentHub portal in MySQL, establishes referential integrity via primary and foreign key constraints, and validates database connectivity through procedural PHP MySQLi.

---

## 2. Answers to Key Interpretation Questions

### Q1: Is the schema normalized properly?
Yes, up to **3rd Normal Form (3NF)**:
- **1NF:** All column attributes are atomic (no repeating groups or multi-valued fields).
- **2NF:** All non-key attributes are fully functionally dependent on primary keys (`students.id`, `events.id`).
- **3NF:** No transitive dependencies exist. Student registration details are isolated into a dedicated junction table (`registrations`) rather than embedded into students or events.

### Q2: Are primary keys and foreign keys correctly defined?
Yes:
- `students.id` is `INT AUTO_INCREMENT PRIMARY KEY`.
- `events.id` is `INT AUTO_INCREMENT PRIMARY KEY`.
- `registrations.id` is `INT AUTO_INCREMENT PRIMARY KEY`.
- `registrations.student_id` is an `INT` Foreign Key referencing `students(id)`.
- `registrations.event_id` is an `INT` Foreign Key referencing `events(id)`.
- A composite unique constraint `UNIQUE KEY (student_id, event_id)` prevents duplicate enrollments.

### Q3: Is the database connection handled securely?
Yes:
- Standard procedural `mysqli_connect()` isolates credentials inside a dedicated `db.php` configuration file.
- The connection state is checked with `mysqli_connect_errno()`.
- UTF-8 encoding is enforced with `mysqli_set_charset($conn, "utf8mb4")` to guard against character injection attacks.
