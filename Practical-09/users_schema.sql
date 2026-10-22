-- Practical 9: Users Table for Authentication & Secure Registration
USE `studenthub`;

CREATE TABLE IF NOT EXISTS `users` (
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

-- Seed an Administrator Account (Default password: admin123 - pre-hashed with BCRYPT)
INSERT INTO `users` (`username`, `full_name`, `email`, `mobile`, `password_hash`, `role`) VALUES
('admin', 'Department Administrator', 'admin@charusat.edu.in', '9876500000', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;
