# Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu & Audit Log

## 1. Overview
This practical implements an administrative monitoring dashboard featuring SQL aggregations (metrics for enrolled students, campus events, registrations, and user accounts), relational `JOIN` queries for recent event enrollments, dynamic menu rendering based on user roles, and an immutable security audit logging pipeline.

---

## 2. Answers to Key Interpretation Questions

### Q1: Are dashboard values loaded dynamically from the database?
Yes. Metric summary widgets execute SQL aggregation functions:
- `SELECT COUNT(*) AS cnt FROM students`
- `SELECT COUNT(*) AS cnt FROM events`
- `SELECT COUNT(*) AS cnt FROM registrations`
- `SELECT COUNT(*) AS cnt FROM users WHERE role = 'student'`
The counts dynamically update as students register or events are modified.

### Q2: Is access restricted to admin users?
Yes. The dashboard inspects `$_SESSION['role'] === 'admin'`. Non-admin or unauthenticated access attempts are blocked and redirected to the login gateway.

### Q3: Are role-based menus working?
Yes. PHP conditional blocks inspect `$_SESSION['role']` inside the `<nav>` component:
- Administrators receive administrative management links: *Manage Students*, *Manage Events*, *AJAX CRUD*, and *Audit Logs*.
- Students receive restricted student-facing links: *Browse Events* and *My Profile*.

### Q4: Are important admin actions recorded in audit_logs?
Yes. The `logAuditAction()` helper function records the executing `user_id`, `username`, `action` (e.g. `LOGIN`, `STUDENT_CREATE`, `EVENT_UPDATE`, `DASHBOARD_VIEW`), descriptive details, client IP address (`REMOTE_ADDR`), and exact timestamp into the MySQL `audit_logs` table.
