-- ================================
-- 1. CREATE DATABASE
-- ================================
CREATE DATABASE IF NOT EXISTS mini_crud
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE mini_crud;

-- ================================
-- 2. USERS TABLE
-- ================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- 3. STUDENTS TABLE
-- ================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- 4. INSERT DEFAULT USERS
-- ================================
-- Passwords are SHA-256 hashed

INSERT INTO users (username, password) VALUES
('suman', SHA2('suman@123',256)),
('admin', SHA2('admin123',256)),
('rahul', SHA2('rahul@123',256)),
('priya', SHA2('priya@123',256)),
('manager', SHA2('manager123',256));

-- ================================
-- 5. SAMPLE STUDENTS DATA (10)
-- ================================

INSERT INTO students (student_id, name, email, course, semester) VALUES
('25MCA001','Amit Kumar','amit.k01@silicon.ac.in','MCA','1st'),
('25MCA002','Sneha Sharma','sneha.s02@silicon.ac.in','MCA','1st'),
('25MCA003','Harshit Anand','harshit.a03@silicon.ac.in','MCA','1st'),
('25MCA004','Priya Das','priya.d04@silicon.ac.in','MCA','1st'),
('25MCA005','Rohan Patel','rohan.p05@silicon.ac.in','MCA','1st'),
('25BCA006','Manish Gupta','manish.g06@silicon.ac.in','BCA','1st'),
('25BCA007','Sweta Mishra','sweta.m07@silicon.ac.in','BCA','1st'),
('25CSE008','Aditya Dash','aditya.d08@silicon.ac.in','B.Tech CSE','1st'),
('25ECE009','Saurav Rout','saurav.r09@silicon.ac.in','B.Tech ECE','1st'),
('25BSC010','Ananya Panda','ananya.p10@silicon.ac.in','B.Sc CS','1st');