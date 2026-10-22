# Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload

## 1. Overview
This practical implements a single-page asynchronous CRUD interface for campus events. Client-side JavaScript interacts with a PHP backend via the `fetch()` API and JSON requests, performing Create, Read, Update, and Delete operations without refreshing or flickering the browser.

---

## 2. Answers to Key Interpretation Questions

### Q1: Do PHP endpoints return valid JSON responses?
Yes. The endpoint sets:
```php
header("Content-Type: application/json; charset=utf-8");
echo json_encode(["status" => "success", "data" => $events]);
```
This guarantees well-formed JSON conforming to modern REST conventions.

### Q2: Are GET and POST requests handled properly?
- `GET`: Returns the active events list or single event by ID.
- `POST` with `action: 'add'`: Adds a new event and returns the newly inserted primary key.
- `POST` with `action: 'update'`: Modifies existing event fields.
- `POST` with `action: 'delete'`: Deletes the target event record.

### Q3: Are loading, success, and error states shown on the frontend?
Yes:
- A placeholder loading row displays while the initial `fetch()` runs.
- Successful actions trigger a green toast notification and instantly repaint the table without reloading the document.
- Network or validation failures trigger red error banners with descriptive explanations.

### Q4: Is input validation performed before database operations?
Yes. Both client-side HTML5 input requirements and server-side PHP empty checks enforce mandatory event titles, dates, and venues before any SQL queries are executed.
