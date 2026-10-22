-- ============================================================
-- StudentHub: Master Database Dump (Complete Semester Project)
-- Subject: Web Development Frameworks (ITUE203) - Practical 15
-- Faculty of Technology & Engineering (FTE) - CHARUSAT
-- ============================================================

CREATE DATABASE IF NOT EXISTS `studenthub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub`;

-- ------------------------------------------------------------
-- 1. Table: users (Authentication & RBAC)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `mobile` VARCHAR(15) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'admin') NOT NULL DEFAULT 'student',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin (Password: admin123) and Student (Password: student123)
INSERT INTO `users` (`username`, `full_name`, `email`, `mobile`, `password_hash`, `role`) VALUES
('admin', 'Campus Administrator', 'admin@charusat.edu.in', '9876500000', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
('rahul12', 'Rahul Patel', 'rahul.patel@charusat.edu.in', '9876543210', '$2y$10$7zB3c9X6L14n4uA5qA1MceF8eU2Z8lX7f7/L4M9X8F1G7N7C9B1yq', 'student');

-- ------------------------------------------------------------
-- 2. Table: students (Directory & Records)
-- ------------------------------------------------------------
CREATE TABLE `students` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `roll_number` VARCHAR(20) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `mobile` VARCHAR(15) NOT NULL,
  `department` VARCHAR(50) NOT NULL,
  `year_of_study` VARCHAR(10) NOT NULL,
  `gender` VARCHAR(10) NOT NULL,
  `cgpa` DECIMAL(3,2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed 20 Students
INSERT INTO `students` (`roll_number`, `full_name`, `email`, `mobile`, `department`, `year_of_study`, `gender`, `cgpa`) VALUES
('25DIT001', 'Aarav Shah', 'aarav.shah@charusat.edu.in', '9825100001', 'IT', '3rd', 'Male', 9.10),
('25DIT002', 'Bhavik Patel', 'bhavik.patel@charusat.edu.in', '9825100002', 'IT', '3rd', 'Male', 8.45),
('25DIT012', 'Rahul Patel', 'rahul.patel@charusat.edu.in', '9876543210', 'IT', '3rd', 'Male', 8.85),
('25DIT023', 'Ananya Dave', 'ananya.dave@charusat.edu.in', '9825100003', 'IT', '3rd', 'Female', 9.35),
('25DIT041', 'Khushi Bhatt', 'khushi.bhatt@charusat.edu.in', '9825100004', 'IT', '1st', 'Female', 8.55),
('25DIT056', 'Pooja Varma', 'pooja.varma@charusat.edu.in', '9825100005', 'IT', '4th', 'Female', 8.90),
('25DIT078', 'Harsh Patel', 'harsh.patel@charusat.edu.in', '9825100006', 'IT', '2nd', 'Male', 8.25),
('25DCSE014', 'Manav Mehta', 'manav.mehta@charusat.edu.in', '9825100007', 'CSE', '2nd', 'Male', 8.70),
('25DCSE022', 'Riya Panchal', 'riya.panchal@charusat.edu.in', '9825100008', 'CSE', '3rd', 'Female', 9.00),
('25DCSE033', 'Ishita Desai', 'ishita.desai@charusat.edu.in', '9825100009', 'CSE', '1st', 'Female', 9.05),
('25DCSE051', 'Neil Christian', 'neil.c@charusat.edu.in', '9825100010', 'CSE', '4th', 'Male', 8.10),
('25DCSE077', 'Yash Makwana', 'yash.makwana@charusat.edu.in', '9825100011', 'CSE', '2nd', 'Male', 8.30),
('25DCSE089', 'Rohan Sharma', 'rohan.sharma@charusat.edu.in', '9825100012', 'CSE', '4th', 'Male', 7.95),
('25DCSE102', 'Dev Soni', 'dev.soni@charusat.edu.in', '9825100013', 'CSE', '3rd', 'Male', 7.90),
('25DCE007', 'Krina Parmar', 'krina.parmar@charusat.edu.in', '9825100014', 'CE', '2nd', 'Female', 8.75),
('25DCE018', 'Kavya Trivedi', 'kavya.trivedi@charusat.edu.in', '9825100015', 'CE', '1st', 'Female', 8.15),
('25DCE045', 'Diya Joshi', 'diya.joshi@charusat.edu.in', '9825100016', 'CE', '2nd', 'Female', 8.40),
('25DCE060', 'Tanvi Parmar', 'tanvi.parmar@charusat.edu.in', '9825100017', 'CE', '4th', 'Female', 8.60),
('25DCE081', 'Jayraj Vaghela', 'jayraj.v@charusat.edu.in', '9825100018', 'CE', '3rd', 'Male', 7.65),
('25DCE092', 'Karan Solanki', 'karan.solanki@charusat.edu.in', '9825100019', 'CE', '3rd', 'Male', 7.80);

-- ------------------------------------------------------------
-- 3. Table: events (Campus Events & Posters)
-- ------------------------------------------------------------
CREATE TABLE `events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `event_date` DATE NOT NULL,
  `event_time` TIME NOT NULL,
  `venue` VARCHAR(100) NOT NULL,
  `coordinator` VARCHAR(100) NOT NULL,
  `max_seats` INT(11) NOT NULL DEFAULT 100,
  `description` TEXT,
  `poster_image` VARCHAR(255) DEFAULT '',
  `status` ENUM('Upcoming', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Upcoming',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `events` (`title`, `category`, `event_date`, `event_time`, `venue`, `coordinator`, `max_seats`, `description`, `status`) VALUES
('Cognizance 2026 Hackathon', 'Technical', '2026-11-15', '09:00:00', 'IT Lab 3', 'Dr. Amit Patel', 120, '24-hour national open-source software and IoT hackathon.', 'Upcoming'),
('AI & Deep Learning Hands-On', 'Technical', '2026-11-20', '14:00:00', 'Seminar Hall 1', 'Prof. Sneha Shah', 80, 'Practical workshop covering transformer architectures and neural nets.', 'Upcoming'),
('Goonj Annual Cultural Night', 'Cultural', '2026-12-05', '18:00:00', 'Central Amphitheatre', 'Prof. Rajesh Joshi', 500, 'Live band battles, drama skits, and traditional dance.', 'Upcoming'),
('Inter-Dept Cricket Tournament', 'Sports', '2026-12-10', '08:30:00', 'University Sports Ground', 'Coach Vikram Singh', 160, 'Annual inter-department 8-a-side leather ball cricket trophy.', 'Upcoming'),
('Full-Stack Web Sprint', 'Technical', '2026-11-25', '10:00:00', 'Computer Lab 4', 'Prof. Hardik Dave', 60, 'Rapid frontend and backend API integration challenge using PHP and MySQL.', 'Upcoming');

-- ------------------------------------------------------------
-- 4. Table: registrations (Enrollments Junction)
-- ------------------------------------------------------------
CREATE TABLE `registrations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `event_id` INT(11) NOT NULL,
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` ENUM('Registered', 'Attended', 'Absent', 'Cancelled') DEFAULT 'Registered',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_event` (`student_id`, `event_id`),
  CONSTRAINT `fk_reg_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reg_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `registrations` (`student_id`, `event_id`, `attendance_status`) VALUES
(3, 1, 'Registered'),
(3, 2, 'Registered'),
(1, 1, 'Registered'),
(4, 3, 'Registered'),
(6, 4, 'Registered');

-- ------------------------------------------------------------
-- 5. Table: audit_logs (Traceability & Security)
-- ------------------------------------------------------------
CREATE TABLE `audit_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `username` VARCHAR(50) NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `details` TEXT,
  `ip_address` VARCHAR(45) DEFAULT '127.0.0.1',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `audit_logs` (`user_id`, `username`, `action`, `details`, `ip_address`) VALUES
(1, 'admin', 'SYSTEM_INIT', 'Initial database migration and schema setup', '127.0.0.1'),
(1, 'admin', 'LOGIN', 'Admin authenticated into control console', '127.0.0.1'),
(1, 'admin', 'SEED_LOAD', 'Loaded 20 students and campus events', '127.0.0.1');
