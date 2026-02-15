-- ============================================================
-- SENG412 Group Project - Database Setup
-- Internet Technologies and Web Applications Development
-- Run this file in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS seng412_project;
USE seng412_project;

-- Drop existing tables (in correct order for foreign keys)
DROP TABLE IF EXISTS gpa_records;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS members;

-- ============================================================
-- Members Table
-- ============================================================
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matric_no VARCHAR(20) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5),
    state_of_origin VARCHAR(50),
    phone VARCHAR(20),
    hobbies TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Courses Table
-- ============================================================
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL,
    course_title VARCHAR(200) NOT NULL,
    credit_units DECIMAL(3,1) NOT NULL,
    department VARCHAR(50),
    lecturer VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Employees Table (Payroll - 50 employees)
-- ============================================================
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id VARCHAR(10) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    department VARCHAR(50),
    hours_worked DECIMAL(6,2) DEFAULT 0,
    hourly_rate DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- GPA Records Table
-- ============================================================
CREATE TABLE gpa_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    course_id INT NOT NULL,
    score INT NOT NULL,
    grade CHAR(1) NOT NULL,
    grade_point INT NOT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Insert Group Members
-- ============================================================
INSERT INTO members (matric_no, full_name, blood_group, state_of_origin, phone, hobbies) VALUES
('21/2352', 'Ameh David Ojoajogwu', 'A+', 'Kogi State', '09025316573', 'Football and listening to music'),
('22/0209', 'Anabraba Dein Emmanuel', 'O+', 'Rivers State', '09150783336', 'Football and gaming'),
('22/0030', 'Aneke Kamsiyochukwu Anthony', 'A', 'Enugu State', '08033491229', 'Listening to music, gaming'),
('22/0004', 'Anuriam Isaac Chigozirim', 'O+', 'Abia State', '07042820926', 'Reading'),
('22/0329', 'Anyaehie Chijike Clinton', 'O+', 'Imo State', '08139919327', 'Football and gaming'),
('22/0279', 'Anyahuru Oluebube Daniel', 'O+', 'Abia State', '07035281949', 'Art and building'),
('22/0044', 'Archie Michael Ubong', 'O+', 'Akwa Ibom State', '07016311706', 'Art and building'),
('21/0532', 'Arise Olatunbosun Joseph', 'A+', 'Ekiti State', '09164452032', 'Playing games and coding'),
('22/0242', 'Atoba Samad Oladeji', 'O+', 'Osun State', '08138259601', 'Listening to music, working');

-- ============================================================
-- Insert Courses
-- ============================================================
INSERT INTO courses (course_code, course_title, credit_units, department, lecturer) VALUES
('SENG412', 'Internet Technologies and Web Applications Development', 3.0, 'Software Engineering', 'Idowu Sunday'),
('COSC430', 'Hands-on JAVA Training', 1.0, 'Software Engineering', 'Bankole Oloruntobi'),
('COSC408', 'Modeling and Simulations', 3.0, 'Software Engineering', 'Sanusi Funmilayo'),
('GEDS002', 'Citizenship Orientation', 0.0, 'Software Engineering', 'Abioye Funke Victoria'),
('SENG490', 'Research Project', 6.0, 'Software Engineering', 'Eweoya Ibukun Onaolapo'),
('SENG402', 'Software Quality Engineering and Testing', 3.0, 'Software Engineering', 'Adegbola Adesoji'),
('SENG404', 'Human Computer Interaction and Emerging Technologies', 3.0, 'Software Engineering', 'Bashwira-Idowu Emmanuella'),
('SENG406', 'Formal Methods Specifications in Software Engineering', 3.0, 'Software Engineering', 'Mgbeahuruike Emmanuel Oluchukwu'),
('GEDS420', 'Biblical Principles in Personal and Professional Life', 3.0, 'Software Engineering', 'Eregare Emmanuel');

-- ============================================================
-- Insert 50 Employees for Payroll
-- ============================================================
INSERT INTO employees (emp_id, full_name, department, hours_worked, hourly_rate, deductions) VALUES
('EMP001', 'Adebayo Oluwaseun', 'Human Resources', 40.00, 2500.00, 12000.00),
('EMP002', 'Okonkwo Chidinma', 'Finance', 42.00, 3200.00, 15000.00),
('EMP003', 'Ibrahim Fatima', 'IT', 38.00, 4000.00, 18000.00),
('EMP004', 'Eze Nnamdi', 'Marketing', 45.00, 2800.00, 13500.00),
('EMP005', 'Adeleke Temitope', 'Operations', 40.00, 3500.00, 16000.00),
('EMP006', 'Okafor Chukwuemeka', 'Sales', 36.00, 2200.00, 10000.00),
('EMP007', 'Balogun Ayomide', 'Admin', 44.00, 2000.00, 9500.00),
('EMP008', 'Nwosu Kelechi', 'Customer Service', 40.00, 2600.00, 12500.00),
('EMP009', 'Akinola Damilola', 'IT', 42.00, 4500.00, 20000.00),
('EMP010', 'Uche Ngozi', 'Finance', 38.00, 3000.00, 14000.00),
('EMP011', 'Adeyemi Boluwatife', 'Human Resources', 40.00, 2400.00, 11500.00),
('EMP012', 'Okoro Chinyere', 'Marketing', 43.00, 2700.00, 13000.00),
('EMP013', 'Suleiman Aisha', 'Operations', 41.00, 3100.00, 15500.00),
('EMP014', 'Igwe Obinna', 'Sales', 39.00, 2300.00, 11000.00),
('EMP015', 'Afolabi Tolulope', 'IT', 44.00, 3800.00, 17000.00),
('EMP016', 'Chukwu Ikenna', 'Admin', 40.00, 2100.00, 10500.00),
('EMP017', 'Bakare Omotola', 'Finance', 42.00, 3400.00, 16500.00),
('EMP018', 'Nwachukwu Chiamaka', 'Customer Service', 38.00, 2500.00, 12000.00),
('EMP019', 'Ogundimu Adeola', 'Human Resources', 40.00, 2600.00, 12500.00),
('EMP020', 'Emeka Chisom', 'Marketing', 45.00, 2900.00, 14000.00),
('EMP021', 'Adeniyi Bukola', 'IT', 41.00, 4200.00, 19000.00),
('EMP022', 'Onyeka Njideka', 'Operations', 39.00, 3300.00, 15000.00),
('EMP023', 'Yusuf Halima', 'Sales', 43.00, 2400.00, 11500.00),
('EMP024', 'Agbaje Kehinde', 'Admin', 40.00, 2200.00, 10000.00),
('EMP025', 'Nwankwo Ugochukwu', 'Finance', 42.00, 3600.00, 17500.00),
('EMP026', 'Olayinka Abayomi', 'Customer Service', 38.00, 2300.00, 11000.00),
('EMP027', 'Iroegbu Adaeze', 'Human Resources', 40.00, 2700.00, 13000.00),
('EMP028', 'Fashola Tunde', 'IT', 44.00, 3900.00, 18000.00),
('EMP029', 'Ogbonna Amarachi', 'Marketing', 41.00, 2600.00, 12500.00),
('EMP030', 'Salami Rashidat', 'Operations', 39.00, 3200.00, 15500.00),
('EMP031', 'Anyanwu Ikechukwu', 'Sales', 43.00, 2500.00, 12000.00),
('EMP032', 'Oladipo Mojisola', 'Finance', 40.00, 3100.00, 15000.00),
('EMP033', 'Ogundele Folasade', 'Admin', 42.00, 2300.00, 11000.00),
('EMP034', 'Maduka Ebere', 'Customer Service', 38.00, 2800.00, 13500.00),
('EMP035', 'Ajayi Olayemi', 'IT', 45.00, 4100.00, 19500.00),
('EMP036', 'Dimgba Chibuzo', 'Human Resources', 40.00, 2500.00, 12000.00),
('EMP037', 'Oyedele Morenike', 'Marketing', 41.00, 2700.00, 13000.00),
('EMP038', 'Onuoha Nneka', 'Operations', 39.00, 3000.00, 14500.00),
('EMP039', 'Kolawole Sunkanmi', 'Sales', 44.00, 2600.00, 12500.00),
('EMP040', 'Ezenwa Somtochukwu', 'Finance', 40.00, 3500.00, 17000.00),
('EMP041', 'Olatunji Abiodun', 'IT', 42.00, 3700.00, 17500.00),
('EMP042', 'Nwobi Kosisochukwu', 'Admin', 38.00, 2100.00, 10000.00),
('EMP043', 'Ogunyemi Adetayo', 'Customer Service', 43.00, 2400.00, 11500.00),
('EMP044', 'Iheanacho Lotanna', 'Human Resources', 40.00, 2800.00, 13500.00),
('EMP045', 'Babatunde Oluwakemi', 'Marketing', 41.00, 3000.00, 14500.00),
('EMP046', 'Udoh Aniekan', 'Operations', 39.00, 3400.00, 16000.00),
('EMP047', 'Awolowo Ifeoluwa', 'Sales', 44.00, 2700.00, 13000.00),
('EMP048', 'Obi Chinazo', 'IT', 42.00, 4300.00, 20000.00),
('EMP049', 'Adegoke Omowunmi', 'Finance', 40.00, 3300.00, 16000.00),
('EMP050', 'Nnaji Chiemerie', 'Admin', 38.00, 2200.00, 10500.00);

-- ============================================================
-- Insert GPA Records (9 students x 9 courses)
-- Grade Scale: A(80-100)=5, B(60-79)=4, C(50-59)=3, D(45-49)=2, E(40-44)=1, F(0-39)=0
-- ============================================================

-- Ameh David Ojoajogwu (member_id=1) — Rank 6, GPA 4.16
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(1, 1, 82, 'A', 5), (1, 2, 80, 'A', 5), (1, 3, 72, 'B', 4), (1, 4, 78, 'B', 4),
(1, 5, 75, 'B', 4), (1, 6, 68, 'B', 4), (1, 7, 74, 'B', 4), (1, 8, 66, 'B', 4), (1, 9, 70, 'B', 4);

-- Anabraba Dein Emmanuel (member_id=2) — Rank 8, GPA 3.88
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(2, 1, 72, 'B', 4), (2, 2, 68, 'B', 4), (2, 3, 65, 'B', 4), (2, 4, 70, 'B', 4),
(2, 5, 74, 'B', 4), (2, 6, 66, 'B', 4), (2, 7, 70, 'B', 4), (2, 8, 58, 'C', 3), (2, 9, 72, 'B', 4);

-- Aneke Kamsiyochukwu Anthony (member_id=3) — Rank 1, GPA 4.88
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(3, 1, 90, 'A', 5), (3, 2, 85, 'A', 5), (3, 3, 83, 'A', 5), (3, 4, 88, 'A', 5),
(3, 5, 86, 'A', 5), (3, 6, 82, 'A', 5), (3, 7, 84, 'A', 5), (3, 8, 78, 'B', 4), (3, 9, 92, 'A', 5);

-- Anuriam Isaac Chigozirim (member_id=4) — Rank 3, GPA 4.52
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(4, 1, 84, 'A', 5), (4, 2, 82, 'A', 5), (4, 3, 76, 'B', 4), (4, 4, 72, 'B', 4),
(4, 5, 81, 'A', 5), (4, 6, 80, 'A', 5), (4, 7, 74, 'B', 4), (4, 8, 68, 'B', 4), (4, 9, 78, 'B', 4);

-- Anyaehie Chijike Clinton (member_id=5) — Rank 9, GPA 3.76
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(5, 1, 72, 'B', 4), (5, 2, 70, 'B', 4), (5, 3, 65, 'B', 4), (5, 4, 68, 'B', 4),
(5, 5, 73, 'B', 4), (5, 6, 55, 'C', 3), (5, 7, 66, 'B', 4), (5, 8, 54, 'C', 3), (5, 9, 74, 'B', 4);

-- Anyahuru Oluebube Daniel (member_id=6) — Rank 4, GPA 4.40
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(6, 1, 76, 'B', 4), (6, 2, 82, 'A', 5), (6, 3, 80, 'A', 5), (6, 4, 75, 'B', 4),
(6, 5, 83, 'A', 5), (6, 6, 70, 'B', 4), (6, 7, 72, 'B', 4), (6, 8, 68, 'B', 4), (6, 9, 74, 'B', 4);

-- Archie Michael Ubong (member_id=7) — Rank 5, GPA 4.28
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(7, 1, 74, 'B', 4), (7, 2, 80, 'A', 5), (7, 3, 72, 'B', 4), (7, 4, 76, 'B', 4),
(7, 5, 80, 'A', 5), (7, 6, 70, 'B', 4), (7, 7, 68, 'B', 4), (7, 8, 66, 'B', 4), (7, 9, 72, 'B', 4);

-- Arise Olatunbosun Joseph (member_id=8) — Rank 2, GPA 4.64
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(8, 1, 85, 'A', 5), (8, 2, 82, 'A', 5), (8, 3, 75, 'B', 4), (8, 4, 80, 'A', 5),
(8, 5, 84, 'A', 5), (8, 6, 72, 'B', 4), (8, 7, 83, 'A', 5), (8, 8, 70, 'B', 4), (8, 9, 88, 'A', 5);

-- Atoba Samad Oladeji (member_id=9) — Rank 7, GPA 4.04
INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES
(9, 1, 76, 'B', 4), (9, 2, 82, 'A', 5), (9, 3, 68, 'B', 4), (9, 4, 72, 'B', 4),
(9, 5, 74, 'B', 4), (9, 6, 66, 'B', 4), (9, 7, 70, 'B', 4), (9, 8, 64, 'B', 4), (9, 9, 72, 'B', 4);
