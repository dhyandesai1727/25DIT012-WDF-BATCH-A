# Practical 12: Event Management CRUD with Poster Upload and File Validation

## 1. Overview
This practical implements complete Event Management for administrators, supporting creation, updating, deletion, and file uploads for promotional posters with strict server-side validation on file extensions (JPG, PNG, GIF) and file sizes ($\le 2$ MB).

---

## 2. Answers to Key Interpretation Questions

### Q1: Are add, update, and delete functionalities implemented correctly?
Yes:
- **Add:** `add_event.php` receives event parameters via POST, checks constraints, moves the poster into `uploads/`, and inserts the record into MySQL.
- **Update:** `edit_event.php` allows editing dates, venue, category, and selectively replacing posters (old files are automatically unlinked to avoid orphaned disk clutter).
- **Delete:** `delete_event.php` deletes the event row and removes the associated poster image from disk via `unlink()`.

### Q2: Is the UI linked with the database correctly?
Yes. The index view queries `SELECT * FROM events ORDER BY event_date ASC` and displays formatted event cards/rows with real image thumbnails, event status badges, and action buttons.

### Q3: Are poster uploads validated for type and size?
Yes:
- **Size Validation:** `$fileSize > 2 * 1024 * 1024` rejects files larger than 2MB.
- **Extension/Type Validation:** `pathinfo($name, PATHINFO_EXTENSION)` checks against an allowlist: `['jpg', 'jpeg', 'png', 'gif']`.
- **Unique Name Generation:** Unique file names (`"event_" . time() . "_" . rand() . ".$ext"`) prevent path traversal or filename collision exploits.

### Q4: Are success and failure messages displayed?
Yes. URL query parameters (`?msg=added`, `?msg=updated`, `?msg=deleted`) display color-coded feedback alerts above the table.
