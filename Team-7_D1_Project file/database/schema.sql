CREATE TABLE students (
    studentId VARCHAR(20) PRIMARY KEY,
    studentName VARCHAR(100) NOT NULL,
    program VARCHAR(100),
    waiver DECIMAL(5,2) DEFAULT 0,
    cgpa DECIMAL(4,2) DEFAULT 0,
    tuitionType VARCHAR(50) DEFAULT 'Regular',
    waiverCategory VARCHAR(50) DEFAULT 'None',
    sscWith DECIMAL(4,2),
    sscWithout DECIMAL(4,2),
    hscWith DECIMAL(4,2),
    hscWithout DECIMAL(4,2),
    gender VARCHAR(20)
);

CREATE TABLE userstudent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    studentId VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentId) REFERENCES students(studentId)
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    studentId VARCHAR(20),
    semester VARCHAR(20),
    courseName VARCHAR(100),
    courseType VARCHAR(50),
    credit DECIMAL(3,1),
    FOREIGN KEY (studentId) REFERENCES students(studentId)
);
