# Practical 11: Student Management CRUD with Search & Filter

## 1. Overview
This practical implements complete Create, Read, Update, and Delete (CRUD) workflows for student records using PHP and MySQLi, with dynamic search (by roll number, name, or email) and departmental filtering. It includes a seed dataset of 20+ records.

---

## 2. Answers to Key Interpretation Questions

### Q1: Are SELECT, INSERT, UPDATE, and DELETE operations working?
Yes:
- **SELECT:** `index.php` queries the `students` table dynamically with `LIKE` and equality operators for filtering.
- **INSERT:** `add_student.php` collects validated student fields and issues an `INSERT INTO students ...` statement.
- **UPDATE:** `edit_student.php` populates inputs via `SELECT WHERE id = ?` and submits an `UPDATE students SET ... WHERE id = ?` query.
- **DELETE:** `delete_student.php` executes `DELETE FROM students WHERE id = ?`.

### Q2: Is search by name/email/course implemented?
Yes. The search input uses `LIKE '%$search%'` across `full_name`, `roll_number`, and `email`, combined with an exact department match (`AND department = '$dept'`).

### Q3: Are delete operations confirmed before execution?
Yes. Every delete button binds an inline confirmation dialog:
```html
onclick="return confirm('Are you sure you want to delete this student record?');"
```
Canceling prevents the browser from following the link, preventing accidental record deletion.
