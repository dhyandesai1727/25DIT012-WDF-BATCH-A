# StudentHub - Software Requirement Specification (SRS)

## 1. Introduction
- **Project Name:** StudentHub
- **Platform:** Web Application (HTML5, CSS3, JavaScript ES6, PHP 8, MySQL)
- **Target Audience:** College Students, Club Organizers, Department Faculty, and Administrators.

## 2. Functional Requirements (FR)
- **FR-1 (User Authentication):** Visitors must be able to register with personal and academic details. Registered users can log in with role-based routing (Student vs Admin).
- **FR-2 (Profile Management):** Students can view and update their profile details.
- **FR-3 (Event Browsing & Search):** Anyone can browse active campus events, search by title, and filter by event category.
- **FR-4 (Admin Event CRUD):** Admins can create new events, upload event posters, edit dates/venues, and delete events.
- **FR-5 (Admin Student Management):** Admins can search students by name, filter by department/year, edit information, and delete records.
- **FR-6 (AJAX Real-Time Updates):** Events table can be modified without a full page reload using Fetch API and PHP JSON endpoints.
- **FR-7 (Audit Trail):** Critical administrative actions (add event, update student, delete record) must be logged in an `audit_logs` table.

## 3. Non-Functional Requirements (NFR)
- **Usability:** Simple, clean, beginner-friendly UI with intuitive navigation and mobile-first responsiveness.
- **Performance:** Fast load times, lightweight CSS, and JSON-based asynchronous calls.
- **Security:** Password hashing (`password_hash`), input sanitization, prepared statements, and session timeout.
- **Accessibility:** Semantic HTML5 elements (`<header>`, `<nav>`, `<main>`, `<article>`, `<footer>`), descriptive `alt` tags, and associated form `<label>` tags.
