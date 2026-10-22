# Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

**Course:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd Sem, B.Tech / Diploma IT (CHARUSAT FTE)  
**Academic Year:** 2026-27  
**Project Title:** StudentHub - University Student & Event Activity Portal  

---

## 1. Project Overview & Problem Definition
StudentHub is a centralized web portal designed for college students and faculty administrators. In many colleges, student information, event announcements, workshop registrations, and feedback forms are scattered across different platforms, noticeboards, and messaging groups.

**StudentHub** provides:
- A unified static and dynamic website for student information.
- Event management where administrators post events and upload posters.
- Student directory and registration system.
- Secure login with role-based access for students and administrators.
- Asynchronous event updates and real-time frontend interaction.

---

## 2. Key Modules & User Roles

### User Roles:
1. **Visitor / Guest:** Can browse Home, About, Events list, FAQs, and submit Contact/Feedback.
2. **Student User:** Can register, login, view their student profile, see registered events, and update details.
3. **Admin User:** Can manage student records (CRUD), create and edit campus events with poster uploads, view statistics, and review audit logs.

### Key Modules:
- **Authentication Module:** Registration, login, session timeout, role redirection, and logout.
- **Student Management Module:** Add, view, edit, search, and filter student records.
- **Event Management Module:** Add, update, view campus events with image poster upload and AJAX CRUD.
- **Interactive UI Module:** Collapsible FAQ accordion, light/dark mode switcher, modal dialogs, and responsive navigation.

---

## 3. Minimum 10 Pages Defined
1. `index.html` - Home Page (Welcome banner, featured events, quick links)
2. `about.html` - About Page (About the institution, vision, mission)
3. `register.html` - Student Registration Page (Frontend & backend validation)
4. `login.html` - Login Page (Role-based authentication)
5. `dashboard.html` - Student Dashboard (Profile summary, notifications)
6. `events.html` - Campus Events Page (Grid listing, filter by category)
7. `profile.html` - Student Profile Page (Personal details, academic status)
8. `contact.html` - Contact Us Page (Campus map, contact form)
9. `admin.html` - Admin Dashboard (Metrics, quick management links)
10. `faq.html` - Frequently Asked Questions (Collapsible accordion)
11. `feedback.html` - Student Feedback Form

---

## 4. Key Questions & Conceptual Analysis

### Q1: What is a URL and what are the parts of a URL?
A **Uniform Resource Locator (URL)** is the global address of a web resource on the Internet.
Example: `https://www.charusat.ac.in:443/studenthub/events.php?category=tech#schedule`
- **Protocol / Scheme (`https://`):** Specifies the protocol used to access the resource (HTTP / HTTPS).
- **Subdomain (`www.`):** Sub-division of the main domain.
- **Domain Name (`charusat.ac.in`):** The registered name or IP address of the server hosting the site.
- **Port (`:443`):** Port number used to connect (80 for HTTP, 443 for HTTPS). Often omitted if standard.
- **Path (`/studenthub/events.php`):** The specific directory or file path on the server.
- **Query String (`?category=tech`):** Key-value parameters passed to dynamic server scripts.
- **Fragment / Hash (`#schedule`):** An anchor bookmark pointing to a specific ID inside the webpage.

### Q2: How is an HTML file processed in a web browser?
1. **Fetching:** The browser sends an HTTP request to the web server and receives the raw HTML byte stream.
2. **Tokenization & Parsing:** The browser converts characters into tokens (`<html>`, `<body>`, `<h1>`) and creates nodes.
3. **DOM Construction:** Nodes are arranged into a tree structure known as the **Document Object Model (DOM)**.
4. **CSSOM Construction:** The browser parses linked CSS files to create the **CSS Object Model (CSSOM)**.
5. **Render Tree:** DOM and CSSOM combine into a Render Tree containing only visible elements and computed styles.
6. **Layout (Reflow):** The browser calculates the exact geometry, coordinates, and dimensions of each element on screen.
7. **Painting:** The browser rasterizes pixels, drawing text, colors, borders, and images onto the screen.

### Q3: How will page navigation flow be managed among all HTML pages?
- A consistent, shared `<header>` with a `<nav>` tag containing relative hyperlinks (`<a href="about.html">About</a>`).
- Active link highlighting on the currently viewed page.
- Consistent breadcrumb navigation on interior pages (`Home > Events > TechFest 2026`).
- Shared footer containing quick sitemap links, privacy terms, and contact details.

### Q4: How will GitHub commits be maintained after each practical?
We maintain a clean Git commit workflow by committing after every completed practical:
```bash
git init
git add .
git commit -m "Practical 01: Project initiation, sitemap, wireframes and README"
git branch -M main
git remote add origin https://github.com/your-username/studenthub-wdf.git
git push -u origin main
```
For each subsequent practical, a descriptive commit is added:
`git commit -m "Practical 02: Created 10 semantic HTML5 pages with accessibility structure"`

---

## 5. Directory Structure
See `project_structure.txt` for the complete folder hierarchy.
