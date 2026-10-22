-- Practical 11: Seed 20+ Student Records for Student Management CRUD
USE `studenthub`;

CREATE TABLE IF NOT EXISTS `students` (
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

-- Seed minimum 20 student records
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
('25DCE092', 'Karan Solanki', 'karan.solanki@charusat.edu.in', '9825100019', 'CE', '3rd', 'Male', 7.80),
('25DCE105', 'Dhruv Prajapati', 'dhruv.p@charusat.edu.in', '9825100020', 'CE', '1st', 'Male', 8.20)
ON DUPLICATE KEY UPDATE `id`=`id`;
