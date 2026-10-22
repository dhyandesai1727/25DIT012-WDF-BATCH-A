-- ============================================================
-- Practical 8: MySQL Schema Design for StudentHub
-- Database Name: studenthub
-- Normalized (3NF) Tables: students, events, registrations
-- ============================================================

CREATE DATABASE IF NOT EXISTS `studenthub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub`;

-- ------------------------------------------------------------
-- Table 1: students
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;

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

-- ------------------------------------------------------------
-- Table 2: events
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
  `poster_image` VARCHAR(255) DEFAULT 'default_poster.png',
  `status` ENUM('Upcoming', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Upcoming',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table 3: registrations (Junction / Linking Table)
-- ------------------------------------------------------------
CREATE TABLE `registrations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `event_id` INT(11) NOT NULL,
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` ENUM('Registered', 'Attended', 'Absent', 'Cancelled') DEFAULT 'Registered',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_event` (`student_id`, `event_id`),
  CONSTRAINT `fk_registrations_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_registrations_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Seed Data: students
-- ------------------------------------------------------------
INSERT INTO `students` (`roll_number`, `full_name`, `email`, `mobile`, `department`, `year_of_study`, `gender`, `cgpa`) VALUES
('25DIT001', 'Aarav Shah', 'aarav.shah@charusat.edu.in', '9825100001', 'IT', '3rd', 'Male', 9.10),
('25DIT012', 'Rahul Patel', 'rahul.patel@charusat.edu.in', '9876543210', 'IT', '3rd', 'Male', 8.85),
('25DIT023', 'Ananya Dave', 'ananya.dave@charusat.edu.in', '9825100003', 'IT', '3rd', 'Female', 9.35),
('25DCE018', 'Kavya Trivedi', 'kavya.trivedi@charusat.edu.in', '9825100004', 'CE', '1st', 'Female', 8.15),
('25DCE045', 'Diya Joshi', 'diya.joshi@charusat.edu.in', '9825100005', 'CE', '2nd', 'Female', 8.40),
('25DCSE014', 'Manav Mehta', 'manav.mehta@charusat.edu.in', '9825100006', 'CSE', '2nd', 'Male', 8.70),
('25DCSE089', 'Rohan Sharma', 'rohan.sharma@charusat.edu.in', '9825100007', 'CSE', '4th', 'Male', 7.95);

-- ------------------------------------------------------------
-- Seed Data: events
-- ------------------------------------------------------------
INSERT INTO `events` (`title`, `category`, `event_date`, `event_time`, `venue`, `coordinator`, `max_seats`, `description`, `status`) VALUES
('Cognizance 2026 Hackathon', 'Technical', '2026-11-15', '09:00:00', 'IT Lab 3', 'Dr. Amit Patel', 120, '24-hour national open-source software and IoT hackathon.', 'Upcoming'),
('AI & Deep Learning Hands-On', 'Technical', '2026-11-20', '14:00:00', 'Seminar Hall 1', 'Prof. Sneha Shah', 80, 'Practical workshop covering transformer models and neural nets.', 'Upcoming'),
('Goonj Annual Cultural Night', 'Cultural', '2026-12-05', '18:00:00', 'Central Amphitheatre', 'Prof. Rajesh Joshi', 500, 'Live band battles, drama skits, and traditional dance.', 'Upcoming'),
('Inter-Dept Cricket Tournament', 'Sports', '2026-12-10', '08:30:00', 'University Sports Ground', 'Coach Vikram Singh', 160, 'Annual inter-department 8-a-side leather ball cricket trophy.', 'Upcoming');

-- ------------------------------------------------------------
-- Seed Data: registrations
-- ------------------------------------------------------------
INSERT INTO `registrations` (`student_id`, `event_id`, `attendance_status`) VALUES
(2, 1, 'Registered'),
(2, 2, 'Registered'),
(1, 1, 'Registered'),
(5, 3, 'Registered'),
(6, 4, 'Registered');
