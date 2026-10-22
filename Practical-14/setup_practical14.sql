-- Practical 14: Audit Logs Table Setup
USE `studenthub`;

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `username` VARCHAR(50) NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `details` TEXT,
  `ip_address` VARCHAR(45) DEFAULT '127.0.0.1',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Sample Audit Logs
INSERT INTO `audit_logs` (`user_id`, `username`, `action`, `details`, `ip_address`) VALUES
(1, 'admin', 'LOGIN', 'Admin logged into control panel', '127.0.0.1'),
(1, 'admin', 'STUDENT_CREATE', 'Added student 25DIT105 Dhruv Prajapati', '127.0.0.1'),
(1, 'admin', 'EVENT_UPDATE', 'Updated venue for Cognizance 2026', '127.0.0.1'),
(1, 'admin', 'AUDIT_VIEW', 'Inspected audit activity log', '127.0.0.1')
ON DUPLICATE KEY UPDATE `id`=`id`;
