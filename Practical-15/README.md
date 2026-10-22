# Practical 15: Final Integration, Testing, Documentation & Local Deployment

## 1. Project Title
**StudentHub - Unified Campus Activity & Student Information Management Portal**  
CHARUSAT FTE | Department of Information Technology  
Subject: Web Development Frameworks (ITUE203) - 3rd Semester  

---

## 2. Integrated Technologies
- **Frontend Presentation:** HTML5 Semantic Elements, CSS3 (CSS Grid & Flexbox, CSS Variables, Media Queries), Vanilla JavaScript ES6+.
- **Backend Architecture:** PHP 8 (Procedural MySQLi, Session Management, RBAC, File Uploads, REST JSON APIs).
- **Database Architecture:** MySQL (InnoDB engine, Foreign Key constraints, Cascading Deletes, Normalized 3NF Schema).
- **Deployment & Server:** Apache HTTP Server & MySQL via XAMPP Control Panel.

---

## 3. Step-by-Step Local Deployment Guide (XAMPP)

### Step 1: Place Files into XAMPP Web Root
Ensure the project directory `wdf` is located in your XAMPP `htdocs` directory:
`C:\xampp\htdocs\wdf`

### Step 2: Start Apache & MySQL Services
1. Open the **XAMPP Control Panel**.
2. Click the **Start** button next to **Apache**.
3. Click the **Start** button next to **MySQL**.
Verify that both modules display a green status indicator.

### Step 3: Import the Master Database Schema
1. Open your web browser and navigate to:
   `http://localhost/phpmyadmin/`
2. Click on the **Import** tab at the top.
3. Click **Choose File** and select `C:\xampp\htdocs\wdf\Practical-15\studenthub_master.sql`.
4. Click the **Import** (or **Go**) button at the bottom of the page.
5. All 5 tables (`users`, `students`, `events`, `registrations`, `audit_logs`) along with all seed data will be created in the `studenthub` database.

### Step 4: Run the Application in Your Browser
- Master Practical Navigator: `http://localhost/wdf/index.html`
- Practical 15 Integrated Portal: `http://localhost/wdf/Practical-15/index.php`

---

## 4. Default Seeded Credentials

| Role | Username | Password | Email | Access Rights |
| :--- | :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | `admin@charusat.edu.in` | Full management (Student CRUD, Event CRUD, Posters, Audit Logs) |
| **Student** | `rahul12` | `student123` | `rahul.patel@charusat.edu.in` | View campus events, register, view student dashboard |

---

## 5. GitHub Repository Workflow & Submission

To commit and push the complete lab repository to GitHub:
```bash
# 1. Initialize Git repository
git init

# 2. Stage all practical files
git add .

# 3. Create initial commit
git commit -m "Complete StudentHub Portal: Practicals 1 to 15 (FTE CHARUSAT ITUE203)"

# 4. Link to GitHub remote repository
git branch -M main
git remote add origin https://github.com/<your-username>/studenthub-wdf.git

# 5. Push to GitHub
git push -u origin main
```
